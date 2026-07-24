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

        // Recent enrollments across all my courses (activity feed)
        $recentEnrollments = Enrollment::whereIn('course_id', $courseIds)
            ->with(['user.department', 'course:id,title,slug'])
            ->latest('enrolled_at')
            ->take(8)
            ->get();

        return view('dashboards.trainer', compact('courses', 'stats', 'recentEnrollments'));
    }

    // ---------- Students enrolled in a course (with progress) ----------
    public function courseStudents(Course $course)
    {
        abort_unless($course->trainer_id === auth()->id(), 403);

        $totalLessons = $course->lessons()->count();

        $enrollments = $course->enrollments()
            ->with(['user.department', 'certificate' => fn ($q) => $q->withoutBlob()])
            ->withCount([
                'lessonProgress as completed_lessons_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->latest('enrolled_at')
            ->get();

        return view('trainer.course-students', compact('course', 'enrollments', 'totalLessons'));
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

        // slug is deliberately NOT changed — so old links/bookmarks don't break
        // NOTE: changing cert_mode does not affect existing certificates — they are frozen
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
        // file optional — if provided, it must match the existing type
        if ($request->hasFile('file')) {
            $rules['file'] = $lesson->type === 'pdf'
                ? ['file', 'mimes:pdf']
                : ['file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-msvideo'];
        }

        $validated = $request->validate($rules);

        $lesson->title            = $validated['title'];
        $lesson->duration_minutes = $validated['duration_minutes'] ?? null;

        if ($request->hasFile('file')) {
            // remove the old file
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

    // ---------- Module delete (+ all its lessons' files) ----------
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
     * Pending + issued certificates — only for the trainer's own courses.
     */
    public function certificates()
    {
        $courseIds = Course::where('trainer_id', auth()->id())->pluck('id');

        // withoutBlob() is essential — otherwise each row's base64 PDF (~6.7MB) loads into
        // memory and just ~40 certificates blow past the page memory limit
        $pending = Certificate::withoutBlob()
            ->where('status', 'pending')
            ->whereHas('enrollment', fn ($q) => $q->whereIn('course_id', $courseIds))
            ->with(['enrollment.user', 'enrollment.course'])
            ->oldest('issued_at')          // oldest first — that one has been waiting the longest
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
     * Certificate file upload — base64 in the DB (NOT on disk, since Render's disk is ephemeral).
     */
    public function uploadCertificate(Request $request, Certificate $certificate)
    {
        $enrollment = $certificate->enrollment;

        // security: only a certificate belonging to the trainer's own course
        abort_unless($enrollment->course->trainer_id === auth()->id(), 403);

        $request->validate([
            // SVG is deliberately not allowed — it can hide a script
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
    //  CERTIFICATE DESIGN (HTML template for auto mode)
    // ==========================================================

    public function certificateDesign()
    {
        $template = CertificateTemplate::where('trainer_id', auth()->id())->first();

        // how many of this trainer's courses are on auto mode
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

        // 🔒 sanitize — strip out all script/iframe/php
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