<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;

class CourseController extends Controller
{
    // Public courses listing — saare published courses
    public function index()
    {
        $courses = Course::where('status', 'published')
            ->with(['department', 'trainer'])
            ->withCount('modules')
            ->latest()
            ->get();

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('courses', compact('courses', 'departments'));
    }

    // Public course detail page — koi bhi published course slug se khulega
    public function show(Course $course)
    {
        // sirf published course public dikhe (draft/archived → 404)
        abort_unless($course->status === 'published', 404);

        // modules + lessons (sort order me) + trainer + department
        $course->load([
            'department',
            'trainer',
            'modules'         => fn ($q) => $q->orderBy('sort_order'),
            'modules.lessons' => fn ($q) => $q->orderBy('sort_order'),
        ]);

        // logged-in student pehle se enrolled hai? (button "Enroll" vs "Go to course" dikhane ke liye)
        $isEnrolled = false;
        if (auth()->check() && auth()->user()->isStudent()) {
            $isEnrolled = Enrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->exists();
        }

        // total lessons count (page pe "X lessons" dikhane ke liye)
        $lessonCount = $course->modules->sum(fn ($m) => $m->lessons->count());

        return view('course-detail', compact('course', 'isEnrolled', 'lessonCount'));
    }
}