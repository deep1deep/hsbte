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

        // department sirf tabhi maano jab valid id ho — warna filter ignore
        $departmentId = is_numeric($departmentId) ? (int) $departmentId : null;

        $courses = Course::where('status', 'published')
            ->with(['department', 'trainer'])
            ->withCount('modules')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($search !== '', function ($query) use ($search) {
                // bracket zaroori hai — warna orWhere upar wale status filter ko
                // bhi bypass kar deta aur draft courses public me dikh jaate
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
            ->latest()
            ->paginate(6)
            ->withQueryString();   // page 2 pe bhi search/filter bane rahen

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('courses', compact('courses', 'departments', 'search', 'departmentId'));
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

        // rating summary + recent reviews for the detail page
        $course->loadCount('reviews')->loadAvg('reviews', 'rating');
        $reviews = $course->reviews()
            ->with('user:id,name')
            ->latest()
            ->take(5)
            ->get();

        // logged-in student pehle se enrolled hai? (button "Enroll" vs "Go to course" dikhane ke liye)
        $isEnrolled = false;
        if (auth()->check() && auth()->user()->isStudent()) {
            $isEnrolled = Enrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->exists();
        }

        // total lessons count (page pe "X lessons" dikhane ke liye)
        $lessonCount = $course->modules->sum(fn ($m) => $m->lessons->count());

        return view('course-detail', compact('course', 'isEnrolled', 'lessonCount', 'reviews'));
    }
}