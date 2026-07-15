<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CertificateController;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // ---------- My Courses (student dashboard) ----------
    public function dashboard()
    {
        $enrollments = Enrollment::where('user_id', auth()->id())
            ->with(['course.department', 'certificate'])
            ->latest()
            ->get();

        return view('dashboards.student', compact('enrollments'));
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
            ->with('certificate')
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

        return view('student.course-player', compact('course', 'enrollment', 'completedLessonIds'));
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
        $totalLessons = $course->modules()->withCount('lessons')->get()->sum('lessons_count');
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

        return back()->with('success', 'Lesson marked complete.');
    }
}