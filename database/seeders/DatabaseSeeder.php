<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\Department;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* ---------------- 1. DEPARTMENTS ---------------- */
        $cse = Department::create(['name' => 'Computer Science & Engineering', 'code' => 'CSE']);
        $ece = Department::create(['name' => 'Electronics & Communication',    'code' => 'ECE']);
        $me  = Department::create(['name' => 'Mechanical Engineering',          'code' => 'ME']);
        $ce  = Department::create(['name' => 'Civil Engineering',               'code' => 'CE']);
        $ee  = Department::create(['name' => 'Electrical Engineering',          'code' => 'EE']);

        /* ---------------- 2. ADMIN ---------------- */
        User::create([
            'name'      => 'HSBTE Admin',
            'email'     => 'admin@123.com',
            'password'  => 'password',          // auto-hashed by the model's cast
            'role'      => 'admin',
            'phone'     => '9000000001',
            'is_active' => true,
        ]);

        /* ---------------- 3. TRAINER ---------------- */
        $trainer = User::create([
            'name'          => 'Gurdeep',
            'email'         => 'trainer@123.com',
            'password'      => 'password',
            'role'          => 'trainer',
            'phone'         => '9000000002',
            'is_active'     => true,
            'department_id' => $cse->id,
            'designation'   => 'Head of Department, CSE',
            'qualification' => 'Computer Science',
        ]);

        /* ---------------- 4. STUDENT (for testing) ---------------- */
        User::create([
            'name'          => 'Test Student',
            'email'         => 'student@123.com',
            'password'      => 'password',
            'role'          => 'student',
            'phone'         => '9000000003',
            'is_active'     => true,
            'enrollment_no' => 'HSBTE24CS001',
            'institute'     => 'Govt Polytechnic, Ambala',
            'semester'      => '3rd',
            'department_id' => $cse->id,
        ]);

        /* ---------------- 5. SAMPLE COURSE ---------------- */
        $course = Course::create([
            'department_id'  => $cse->id,
            'trainer_id'     => $trainer->id,
            'title'          => 'Cyber Security Awareness',
            'slug'           => 'cyber-security-awareness',
            'description'    => 'Learn the fundamentals of staying safe online — passwords, phishing, safe browsing, and protecting your digital identity. Free for all Haryana polytechnic students.',
            'thumbnail'      => null,
            'duration_weeks' => 8,
            'status'         => 'published',
            'is_paid'        => false,
            'price'          => 0,
        ]);

        /* ---------------- 6. MODULES + LESSONS ---------------- */
        $week1 = Module::create([
            'course_id'  => $course->id,
            'title'      => 'Week 1: Getting Started',
            'sort_order' => 1,
        ]);

        Lesson::create([
            'module_id'              => $week1->id,
            'title'                  => 'What is Cyber Security?',
            'type'                   => 'video',
            'duration_minutes'       => 12,
            'sort_order'             => 1,
            'video_path'             => null,
            'video_duration_seconds' => 720,
        ]);
        Lesson::create([
            'module_id'        => $week1->id,
            'title'            => 'Course Handbook (PDF)',
            'type'             => 'pdf',
            'duration_minutes' => 5,
            'sort_order'       => 2,
            'file_path'        => null,
        ]);

        $week2 = Module::create([
            'course_id'  => $course->id,
            'title'      => 'Week 2: Passwords & Phishing',
            'sort_order' => 2,
        ]);

        Lesson::create([
            'module_id'              => $week2->id,
            'title'                  => 'Creating Strong Passwords',
            'type'                   => 'video',
            'duration_minutes'       => 15,
            'sort_order'             => 1,
            'video_path'             => null,
            'video_duration_seconds' => 900,
        ]);
        Lesson::create([
            'module_id'              => $week2->id,
            'title'                  => 'Spotting Phishing Emails',
            'type'                   => 'video',
            'duration_minutes'       => 10,
            'sort_order'             => 2,
            'video_path'             => null,
            'video_duration_seconds' => 600,
        ]);

        /* ---------------- 7. ANNOUNCEMENTS (match homepage marquee) ---------------- */
        Announcement::create([
            'title'        => 'Cyber Security Awareness programme registrations open',
            'body'         => 'Last date to register: 15 August 2026. Free for all polytechnic students.',
            'is_active'    => true,
            'published_at' => now(),
        ]);
        Announcement::create([
            'title'        => 'AI & Emerging Tech course starting soon',
            'body'         => 'Registrations close 1 September 2026. Limited seats available.',
            'is_active'    => true,
            'published_at' => now()->subDay(),
        ]);
        Announcement::create([
            'title'        => 'Digital Governance workshop announced',
            'body'         => 'Online mode. Register before 10 September 2026.',
            'is_active'    => true,
            'published_at' => now()->subDays(2),
        ]);
    }
}