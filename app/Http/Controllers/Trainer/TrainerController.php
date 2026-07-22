<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Department;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Support\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TrainerController extends Controller
{
    public function dashboard()
    {
        $trainer = auth()->user();

        $courses = Course::where('trainer_id', $trainer->id)
            ->with('department')
            ->withCount(['modules', 'enrollments'])
            ->latest()
            ->get();

        $courseIds = $courses->pluck('id');
        $stats = [
            'courses'   => $courses->count(),
            'published' => $courses->where('status', 'published')->count(),
            'students'  => Enrollment::whereIn('course_id', $courseIds)
                                ->distinct('user_id')
                                ->count('user_id'),
            'pending'   => Certificate::where('status', 'pending')
                                ->whereHas('enrollment', fn ($q) => $q->whereIn('course_id', $courseIds))
                                ->count(),
        ];

        return view('dashboards.trainer', compact('courses', 'stats'));
    }

    public function createCourse()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $hasDesign   = CertificateTemplate::where('trainer_id', auth()->id())->exists();

        return view('trainer.course-create', compact('departments', 'hasDesign'));
    }

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'department_id'  => ['required', 'exists:departments,id'],
            'duration_weeks' => ['nullable', 'integer', 'min:1', 'max:104'],
            'status'         => ['required', 'in:draft,published'],
            'cert_mode'      => ['required', 'in:manual,auto'],
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $i = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        Course::create([
            'trainer_id'     => auth()->id(),
            'department_id'  => $validated['department_id'],
            'title'          => $validated['title'],
            'slug'           => $slug,
            'description'    => $validated['description'] ?? null,
            'duration_weeks' => $validated['duration_weeks'] ?? null,
            'status'         => $validated['status'],
            'cert_mode'      => $validated['cert_mode'],
            'is_paid'        => false,
            'price'          => 0,
        ]);

        return redirect()->route('trainer.dashboard')
            ->with('success', 'Course created successfully.');
    }

    // ---------- Manage page ----------
    public function manageCourse(Course $course)
    {
        abort_unless($course->trainer_id === auth()->id(), 403);

        $course->load([
            'modules'         => fn ($q) => $q->orderBy('sort_order'),
            'modules.lessons' => fn ($q) => $q->orderBy('sort_order'),
        ]);

        return view('trainer.course-manage', compact('course'));
    }

    // ---------- Edit course (form) ----------
    public function editCourse(Course $course)
    {
        abort_unless($course->trainer_id === auth()->id(), 403);

        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $hasDesign   = CertificateTemplate::where('trainer_id', auth()->id())->exists();

        return view('trainer.course-edit', compact('course', 'departments', 'hasDesign'));
    }

    // ---------- Update course ----------
    public function updateCourse(Request $request, Course $course)
    {
        abort_unless($course->trainer_id === auth()->id(), 403);

        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'department_id'  => ['required', 'exists:departments,id'],
            'duration_weeks' => ['nullable', 'integer', 'min:1', 'max:104'],
            'status'         => ['required', 'in:draft,published'],
            'cert_mode'      => ['required', 'in:manual,auto'],
        ]);

        // slug jaanbujhkar NAHI badla — purane links/bookmarks na tooten
        // NOTE: cert_mode badalne se purane certificates par asar nahi — wo frozen hain
        $course->update([
            'title'          => $validated['title'],
            'description'    => $validated['description'] ?? null,
            'department_id'  => $validated['department_id'],
            'duration_weeks' => $validated['duration_weeks'] ?? null,
            'status'         => $validated['status'],
            'cert_mode'      => $validated['cert_mode'],
        ]);

        return redirect()->route('trainer.courses.manage', $course)
            ->with('success', 'Course details updated.');
    }

    // ---------- Module add ----------
    public function storeModule(Request $request, Course $course)
    {
        abort_unless($course->trainer_id === auth()->id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        Module::create([
            'course_id'  => $course->id,
            'title'      => $validated['title'],
            'sort_order' => ($course->modules()->max('sort_order') ?? 0) + 1,
        ]);

        return back()->with('success', 'Module added.');
    }

    // ---------- Module update ----------
    public function updateModule(Request $request, Module $module)
    {
        abort_unless($module->course->trainer_id === auth()->id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $module->update(['title' => $validated['title']]);

        return back()->with('success', 'Module updated.');
    }

    // ---------- Lesson add (video / pdf upload) ----------
    public function storeLesson(Request $request, Module $module)
    {
        abort_unless($module->course->trainer_id === auth()->id(), 403);

        $rules = [
            'title'            => ['required', 'string', 'max:255'],
            'type'             => ['required', 'in:video,pdf'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ];
        $rules['file'] = $request->input('type') === 'pdf'
            ? ['required', 'file', 'mimes:pdf']
            : ['required', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-msvideo'];

        $validated = $request->validate($rules);

        $folder = $validated['type'] === 'pdf' ? 'lessons/pdfs' : 'lessons/videos';
        $path   = $request->file('file')->store($folder, 'public');

        Lesson::create([
            'module_id'        => $module->id,
            'title'            => $validated['title'],
            'type'             => $validated['type'],
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'sort_order'       => ($module->lessons()->max('sort_order') ?? 0) + 1,
            'video_path'       => $validated['type'] === 'video' ? $path : null,
            'file_path'        => $validated['type'] === 'pdf'   ? $path : null,
        ]);

        return back()->with('success', 'Lesson added.');
    }

    // ---------- Lesson update (title/duration + optional file replace) ----------
    public function updateLesson(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->module->course->trainer_id === auth()->id(), 403);

        $rules = [
            'title'            => ['required', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ];
        // file optional — ho to purane type ka hi hona chahiye
        if ($request->hasFile('file')) {
            $rules['file'] = $lesson->type === 'pdf'
                ? ['file', 'mimes:pdf']
                : ['file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-msvideo'];
        }

        $validated = $request->validate($rules);

        $lesson->title            = $validated['title'];
        $lesson->duration_minutes = $validated['duration_minutes'] ?? null;

        if ($request->hasFile('file')) {
            // purani file hatao
            $old = $lesson->type === 'pdf' ? $lesson->file_path : $lesson->video_path;
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $folder = $lesson->type === 'pdf' ? 'lessons/pdfs' : 'lessons/videos';
            $path   = $request->file('file')->store($folder, 'public');

            if ($lesson->type === 'pdf') {
                $lesson->file_path = $path;
            } else {
                $lesson->video_path = $path;
            }
        }

        $lesson->save();

        return back()->with('success', 'Lesson updated.');
    }

    // ---------- Lesson delete (+ file cleanup) ----------
    public function destroyLesson(Lesson $lesson)
    {
        abort_unless($lesson->module->course->trainer_id === auth()->id(), 403);

        foreach ([$lesson->video_path, $lesson->file_path] as $p) {
            if ($p) {
                Storage::disk('public')->delete($p);
            }
        }

        $lesson->delete();

        return back()->with('success', 'Lesson deleted.');
    }

    // ---------- Module delete (+ uske sab lessons ki files) ----------
    public function destroyModule(Module $module)
    {
        abort_unless($module->course->trainer_id === auth()->id(), 403);

        foreach ($module->lessons as $lesson) {
            foreach ([$lesson->video_path, $lesson->file_path] as $p) {
                if ($p) {
                    Storage::disk('public')->delete($p);
                }
            }
        }

        $module->delete();

        return back()->with('success', 'Module deleted.');
    }

    // ---------- Publish / Unpublish toggle ----------
    public function togglePublish(Course $course)
    {
        abort_unless($course->trainer_id === auth()->id(), 403);

        $course->status = $course->status === 'published' ? 'draft' : 'published';
        $course->save();

        $msg = $course->status === 'published'
            ? 'Course published — students can see it now.'
            : 'Course unpublished — hidden from students.';

        return back()->with('success', $msg);
    }

    // ==========================================================
    //  CERTIFICATES (manual upload)
    // ==========================================================

    /**
     * Pending + issued certificates — sirf mere courses ke.
     */
    public function certificates()
    {
        $courseIds = Course::where('trainer_id', auth()->id())->pluck('id');

        // withoutBlob() zaroori hai — warna har row ka base64 PDF (~6.7MB) memory me
        // aata hai aur ~40 certificates pe hi page memory limit tod deta hai
        $pending = Certificate::withoutBlob()
            ->where('status', 'pending')
            ->whereHas('enrollment', fn ($q) => $q->whereIn('course_id', $courseIds))
            ->with(['enrollment.user', 'enrollment.course'])
            ->oldest('issued_at')          // sabse purana pehle — wahi sabse zyada wait kar raha hai
            ->get();

        $issued = Certificate::withoutBlob()
            ->where('status', 'issued')
            ->whereHas('enrollment', fn ($q) => $q->whereIn('course_id', $courseIds))
            ->with(['enrollment.user', 'enrollment.course'])
            ->latest('issued_at')
            ->get();

        return view('trainer.certificates', compact('pending', 'issued'));
    }

    /**
     * Certificate file upload — DB me base64 (disk pe NAHI, Render disk ephemeral hai).
     */
    public function uploadCertificate(Request $request, Certificate $certificate)
    {
        $enrollment = $certificate->enrollment;

        // security: sirf apne course ka certificate
        abort_unless($enrollment->course->trainer_id === auth()->id(), 403);

        $request->validate([
            // SVG jaanbujhkar allowed nahi — usme script chhup sakti hai
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'file.max'   => 'The file must be smaller than 5MB.',
            'file.mimes' => 'Only PDF, JPG or PNG files are allowed.',
        ]);

        $file = $request->file('file');

        $certificate->update([
            'status'      => 'issued',
            'source'      => 'manual',
            'file_blob'   => base64_encode(file_get_contents($file->getRealPath())),
            'file_mime'   => $file->getMimeType(),
            'uploaded_by' => auth()->id(),
            'issued_at'   => now(),
        ]);

        return back()->with('success', 'Certificate issued — the student can download it now.');
    }

    // ==========================================================
    //  CERTIFICATE DESIGN (auto mode ka HTML template)
    // ==========================================================

    public function certificateDesign()
    {
        $template = CertificateTemplate::where('trainer_id', auth()->id())->first();

        // is trainer ke kitne course auto mode pe hain
        $autoCourses = Course::where('trainer_id', auth()->id())
            ->where('cert_mode', 'auto')
            ->pluck('title');

        return view('trainer.certificate-design', [
            'template'     => $template,
            'placeholders' => HtmlSanitizer::placeholders(),
            'autoCourses'  => $autoCourses,
        ]);
    }

    public function saveCertificateDesign(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'html' => ['required', 'string', 'max:512000'],   // ~500KB
        ], [
            'html.max'      => 'The design must be smaller than 500KB.',
            'html.required' => 'HTML cannot be empty.',
        ]);

        // 🔒 sanitize — script/iframe/php sab nikal do
        $clean = HtmlSanitizer::clean($validated['html']);

        abort_if($clean === '', 422, 'Nothing left after sanitizing the HTML.');

        CertificateTemplate::updateOrCreate(
            ['trainer_id' => auth()->id()],
            [
                'name'      => $validated['name'],
                'html'      => $clean,
                'is_active' => true,
            ]
        );

        return back()->with('success', 'Certificate design saved. Auto-mode courses will use it from now on.');
    }
}