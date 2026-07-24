<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Public courses listing — search + department filter + 6 per page
    public function index(Request $request)
    {
        $search       = trim((string) $request->query('q', ''));
        $departmentId = $request->query('department');

        // only honor the department when it's a valid id — otherwise ignore the filter
        $departmentId = is_numeric($departmentId) ? (int) $departmentId : null;

        $courses = Course::where('status', 'published')
            ->with(['department', 'trainer'])
            ->withCount('modules')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($search !== '', function ($query) use ($search) {
                // the bracket is essential — otherwise orWhere would also bypass the
                // status filter above and draft courses would show up publicly
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
            ->latest()
            ->paginate(6)
            ->withQueryString();   // keep search/filter on page 2 as well

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('courses', compact('courses', 'departments', 'search', 'departmentId'));
    }

    // Public course detail page — any published course opens by its slug
    public function show(Course $course)
    {
        // only published courses are shown publicly (draft/archived → 404)
        abort_unless($course->status === 'published', 404);

        // modules + lessons (in sort order) + trainer + department
        $course->load([
            'department',
            'trainer',
            'modules'         => fn ($q) => $q->orderBy('sort_order'),
            'modules.lessons' => fn ($q) => $q->orderBy('sort_order'),
        ]);

        // rating summary + recent reviews for the detail page
        $course->loadCount('reviews')->loadAvg('reviews', 'rating');
        $reviews = $course->reviews()
            ->with('user:id,name')
            ->latest()
            ->take(5)
            ->get();

        // is the logged-in student already enrolled? (to show the "Enroll" vs "Go to course" button)
        $isEnrolled = false;
        if (auth()->check() && auth()->user()->isStudent()) {
            $isEnrolled = Enrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->exists();
        }

        // total lessons count (to show "X lessons" on the page)
        $lessonCount = $course->modules->sum(fn ($m) => $m->lessons->count());

        return view('course-detail', compact('course', 'isEnrolled', 'lessonCount', 'reviews'));
    }
}