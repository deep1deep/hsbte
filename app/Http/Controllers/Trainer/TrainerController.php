<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrainerController extends Controller
{
    public function dashboard()
    {
        $trainer = auth()->user();

        // Sirf is trainer ke courses — with module + enrollment counts
        $courses = Course::where('trainer_id', $trainer->id)
            ->with('department')
            ->withCount(['modules', 'enrollments'])
            ->latest()
            ->get();

        // Stats
        $courseIds = $courses->pluck('id');
        $stats = [
            'courses'   => $courses->count(),
            'published' => $courses->where('status', 'published')->count(),
            'students'  => Enrollment::whereIn('course_id', $courseIds)
                                ->distinct('user_id')
                                ->count('user_id'),
        ];

        return view('dashboards.trainer', compact('courses', 'stats'));
    }
    // Create Course form dikhao
    public function createCourse()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('trainer.course-create', compact('departments'));
    }

    // Naya course save karo
    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'department_id'  => ['required', 'exists:departments,id'],
            'duration_weeks' => ['nullable', 'integer', 'min:1', 'max:104'],
            'status'         => ['required', 'in:draft,published'],
        ]);

        // Title se unique slug banao
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $i = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        Course::create([
            'trainer_id'     => auth()->id(),   // 🔒 owner = logged-in trainer
            'department_id'  => $validated['department_id'],
            'title'          => $validated['title'],
            'slug'           => $slug,
            'description'    => $validated['description'] ?? null,
            'duration_weeks' => $validated['duration_weeks'] ?? null,
            'status'         => $validated['status'],
            'is_paid'        => false,   // abhi sab free
            'price'          => 0,
        ]);

        return redirect()->route('trainer.dashboard')
            ->with('success', 'Course created successfully.');
    }
}