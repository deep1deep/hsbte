<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        // 30-day enrollment trend (grouped in PHP → DB-agnostic)
        $trend = $this->enrollmentTrend(30);

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
            'stats', 'students', 'trainers', 'courseEnrollments', 'departments', 'trend'
        ));
    }

    /**
     * Enrollments per day for the last N days.
     * Returns ['labels' => [...], 'counts' => [...], 'max' => int, 'total' => int].
     */
    private function enrollmentTrend(int $days): array
    {
        $since = now()->subDays($days - 1)->startOfDay();

        // one query, group in PHP so it works on sqlite/mysql alike
        $rows = Enrollment::where('enrolled_at', '>=', $since)
            ->get(['enrolled_at'])
            ->groupBy(fn ($e) => optional($e->enrolled_at)->format('Y-m-d'));

        $labels = [];
        $counts = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $since->copy()->addDays($i);
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d M');
            $counts[] = isset($rows[$key]) ? $rows[$key]->count() : 0;
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
            'max'    => max(1, max($counts)),
            'total'  => array_sum($counts),
        ];
    }

    /* ================= STUDENTS ================= */

    public function students(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $deptId = $request->get('department');

        $students = User::where('role', 'student')
            ->with('department')
            ->withCount('enrollments')
            ->when($search, fn ($query) => $query->where(fn ($w) =>
                $w->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
            ))
            ->when($deptId, fn ($query) => $query->where('department_id', $deptId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.students', compact('students', 'departments', 'search', 'deptId'));
    }

    public function exportStudents(Request $request): StreamedResponse
    {
        $search = trim((string) $request->get('q', ''));
        $deptId = $request->get('department');

        $students = User::where('role', 'student')
            ->with('department')
            ->withCount('enrollments')
            ->when($search, fn ($query) => $query->where(fn ($w) =>
                $w->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
            ))
            ->when($deptId, fn ($query) => $query->where('department_id', $deptId))
            ->latest()
            ->get();

        $filename = 'students-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($students) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Email', 'Phone', 'Department', 'Semester', 'Institute', 'Courses', 'Registered']);
            foreach ($students as $s) {
                fputcsv($out, [
                    $s->name,
                    $s->email,
                    $s->phone,
                    $s->department->name ?? '',
                    $s->semester,
                    $s->institute,
                    $s->enrollments_count,
                    optional($s->created_at)->format('Y-m-d'),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /* ================= COURSES ================= */

    public function courses(Request $request)
    {
        $status = $request->get('status');

        $courses = Course::with(['department', 'trainer'])
            ->withCount(['enrollments', 'modules'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all'       => Course::count(),
            'published' => Course::where('status', 'published')->count(),
            'draft'     => Course::where('status', 'draft')->count(),
            'archived'  => Course::where('status', 'archived')->count(),
        ];

        return view('admin.courses', compact('courses', 'status', 'counts'));
    }

    public function updateCourseStatus(Request $request, Course $course)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $course->update(['status' => $validated['status']]);

        return back()->with('success', "\"{$course->title}\" is now {$validated['status']}.");
    }

    /* ================= TRAINERS ================= */

    public function updateTrainer(Request $request, User $trainer)
    {
        abort_unless($trainer->role === 'trainer', 404);

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:15', 'unique:users,phone,' . $trainer->id],
            'department_id' => ['required', 'exists:departments,id'],
            'designation'   => ['nullable', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:255'],
        ], [
            'phone.unique' => 'This phone number is already registered.',
        ]);

        $trainer->update($validated);

        return back()->with('success', 'Trainer details updated.');
    }

    public function toggleTrainer(User $trainer)
    {
        abort_unless($trainer->role === 'trainer', 404);

        $trainer->update(['is_active' => ! $trainer->is_active]);

        $state = $trainer->is_active ? 'enabled' : 'disabled';

        return back()->with('success', "Trainer account {$state}.");
    }

    public function resetTrainerPassword(Request $request, User $trainer)
    {
        abort_unless($trainer->role === 'trainer', 404);

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $trainer->update(['password' => $request->password]);

        return back()->with('success', "Password reset for {$trainer->name}.");
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