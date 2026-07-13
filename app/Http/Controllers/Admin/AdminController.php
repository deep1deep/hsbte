<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Top stat cards
        $stats = [
            'students'    => User::where('role', 'student')->count(),
            'trainers'    => User::where('role', 'trainer')->count(),
            'courses'     => Course::count(),
            'enrollments' => Enrollment::count(),
        ];

        // Recent students (newest 10) with their department
        $students = User::where('role', 'student')
            ->with('department')
            ->latest()
            ->take(10)
            ->get();

        // All trainers with department
        $trainers = User::where('role', 'trainer')
            ->with('department')
            ->latest()
            ->get();

        // How many enrolled per course
        $courseEnrollments = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->get();

        // Departments for the "Add Trainer" dropdown
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboards.admin', compact(
            'stats', 'students', 'trainers', 'courseEnrollments', 'departments'
        ));
    }

    // Admin creates a trainer account
    public function storeTrainer(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'phone'         => ['nullable', 'string', 'max:15', 'unique:users,phone'],
            'department_id' => ['required', 'exists:departments,id'],
            'designation'   => ['nullable', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'password'      => ['required', 'string', 'min:8'],
        ], [
            'email.unique' => 'This email is already registered.',
            'phone.unique' => 'This phone number is already registered.',
        ]);

        User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'department_id' => $validated['department_id'],
            'designation'   => $validated['designation'] ?? null,
            'qualification' => $validated['qualification'] ?? null,
            'password'      => $validated['password'],  // model cast auto-hashes it
            'role'          => 'trainer',   // 🔒 FORCED — never taken from the form
            'is_active'     => true,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Trainer added successfully.');
    }
}