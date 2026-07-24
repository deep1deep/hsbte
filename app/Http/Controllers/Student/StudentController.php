<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CertificateController;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    // ---------- My Courses (student dashboard) ----------
    public function dashboard()
    {
        $enrollments = Enrollment::where('user_id', auth()->id())
            ->with([
                // lessons_count → progressPercent() bina extra query ke chalega
                'course' => fn ($q) => $q->withCount('lessons'),
                'course.department',
                // blob (base64 PDF, ~6.7MB) list page pe kabhi chahiye hi nahi
                'certificate' => fn ($q) => $q->withoutBlob(),
            ])
            ->withCount([
                'lessonProgress as completed_lessons_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->latest()
            ->get();

        // "Continue learning" — sabse recent in-progress course jisme thoda kaam ho chuka
        $continue = $enrollments->first(fn ($e) =>
            $e->status !== 'completed' && $e->progressPercent() > 0
        )
        // koi bhi shuru nahi kiya to pehla active course "Start" ke liye
        ?? $enrollments->first(fn ($e) => $e->status !== 'completed');

        // "Courses you may like" — apne department ke published course jo enroll nahi kiye
        $enrolledIds = $enrollments->pluck('course_id');
        $recommended = collect();

        if ($user = auth()->user()) {
            $recommended = Course::where('status', 'published')
                ->whereNotIn('id', $enrolledIds)
                ->when($user->department_id, fn ($q) =>
                    $q->orderByRaw('department_id = ? DESC', [$user->department_id])
                )
                ->with('department')
                ->withCount('enrollments')
                ->latest()
                ->take(3)
                ->get();
        }

        return view('dashboards.student', compact('enrollments', 'continue', 'recommended'));
    }

    // ---------- Profile (view + edit own details) ----------
    public function profile()
    {
        $user = auth()->user();
        $user->loadCount('enrollments');

        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:15', 'unique:users,phone,' . $user->id],
            'institute'     => ['nullable', 'string', 'max:255'],
            'semester'      => ['nullable', 'string', 'max:20'],
        ], [
            'phone.unique' => 'This phone number is already registered.',
        ]);

        $user->update($validated);

        return redirect()->route('student.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Your current password is incorrect.',
        ]);

        auth()->user()->update([
            'password' => $request->password,   // model cast auto-hashes
        ]);

        return redirect()->route('student.profile')
            ->with('success', 'Password changed successfully.');
    }

    // ---------- Enroll into a course ----------
    public function enroll(Course $course)
    {
        $user = auth()->user();

        // sirf student enroll kar sakta (trainer/admin nahi) — 403 ki jagah gently wapas
        if (! $user->isStudent()) {
            return redirect()->route('course.detail', $course)
                ->with('success', 'Only students can enroll in courses.');
        }

        // sirf published course mein enroll
        abort_unless($course->status === 'published', 404);

        // pehle se enrolled? to double na ho — bas player pe bhej do
        $already = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($already) {
            return redirect()->route('student.course.show', $course)
                ->with('success', 'You are already enrolled.');
        }

        Enrollment::create([
            'user_id'     => $user->id,
            'course_id'   => $course->id,
            'status'      => 'active',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('student.course.show', $course)
            ->with('success', 'Enrolled successfully. Start learning!');
    }

    // ---------- Course player (watch lessons) ----------
    public function showCourse(Course $course)
    {
        // enrolled hai ya nahi — na ho to 403
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->with([
                'course' => fn ($q) => $q->withCount('lessons'),
                'certificate' => fn ($q) => $q->withoutBlob(),
            ])
            ->withCount([
                'lessonProgress as completed_lessons_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->first();

        abort_unless($enrollment, 403, 'You are not enrolled in this course.');

        $course->load([
            'modules'         => fn ($q) => $q->orderBy('sort_order'),
            'modules.lessons' => fn ($q) => $q->orderBy('sort_order'),
        ]);

        // is student ne kaun-kaun se lesson complete kiye
        $completedLessonIds = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')
            ->pluck('lesson_id')
            ->all();

        // First lesson (in order) not yet completed — the "resume / next up" target.
        $nextLessonId = $this->firstIncompleteLessonId($course, $completedLessonIds);

        return view('student.course-player', compact(
            'course', 'enrollment', 'completedLessonIds', 'nextLessonId'
        ));
    }

    // Walk modules → lessons in order, return the first id not in $completedIds.
    private function firstIncompleteLessonId(Course $course, array $completedIds): ?int
    {
        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                if (! in_array($lesson->id, $completedIds, true)) {
                    return $lesson->id;
                }
            }
        }

        return null;
    }

    // ---------- Mark a lesson complete ----------
    public function markComplete(Request $request, Lesson $lesson)
    {
        $course = $lesson->module->course;

        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        // progress row: pehle se ho to update, na ho to bana
        LessonProgress::updateOrCreate(
            ['enrollment_id' => $enrollment->id, 'lesson_id' => $lesson->id],
            ['status' => 'completed', 'completed_at' => now()]
        );

        // saare lessons complete ho gaye? to course bhi completed + CERTIFICATE
        // ek query — pehle har module fetch karke PHP me sum karta tha
        $totalLessons = $course->lessons()->count();
        $doneLessons  = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')->count();

        if ($totalLessons > 0 && $doneLessons >= $totalLessons) {
            $enrollment->update(['status' => 'completed', 'completed_at' => now()]);

            // 🎓 course complete → certificate (auto = ready | manual = pending)
            $certificate = CertificateController::generateFor($enrollment);

            $msg = $certificate->isPending()
                ? 'Course completed! 🎉 Your certificate is with the trainer for approval — you will be able to download it soon.'
                : 'Course completed! Your certificate is ready. 🎉';

            return back()->with('success', $msg);
        }

        // Not finished yet — send them straight to the next incomplete lesson.
        $completedIds = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')->pluck('lesson_id')->all();
        $course->load([
            'modules'         => fn ($q) => $q->orderBy('sort_order'),
            'modules.lessons' => fn ($q) => $q->orderBy('sort_order'),
        ]);
        $nextId = $this->firstIncompleteLessonId($course, $completedIds);

        return redirect()
            ->route('student.course.show', $course)
            ->withFragment($nextId ? 'lesson-' . $nextId : 'course-top')
            ->with('success', 'Lesson marked complete. On to the next one!');
    }
}