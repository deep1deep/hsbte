<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Department;

class HomeController extends Controller
{
    public function index()
    {
        // Featured: latest 3 published courses
        $courses = Course::where('status', 'published')
            ->with(['department', 'trainer'])
            ->withCount('enrollments')
            ->latest()
            ->take(3)
            ->get();

        // Announcements — marquee + notice board dono ke liye
        $announcements = Announcement::active()->take(6)->get();

        // Real stats (jaisa data badhega, apne aap badhega)
        $stats = [
            'students'    => User::where('role', 'student')->count(),
            'courses'     => Course::where('status', 'published')->count(),
            'trainers'    => User::where('role', 'trainer')->count(),
            'departments' => Department::where('is_active', true)->count(),
        ];

        return view('home', compact('courses', 'announcements', 'stats'));
    }
}