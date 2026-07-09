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
        /* ---------------- 1. DEPARTMENTS (unique: code) ---------------- */
        $cse = Department::firstOrCreate(['code' => 'CSE'], ['name' => 'Computer Science & Engineering']);
        $ece = Department::firstOrCreate(['code' => 'ECE'], ['name' => 'Electronics & Communication']);
        $me  = Department::firstOrCreate(['code' => 'ME'],  ['name' => 'Mechanical Engineering']);
        $ce  = Department::firstOrCreate(['code' => 'CE'],  ['name' => 'Civil Engineering']);
        $ee  = Department::firstOrCreate(['code' => 'EE'],  ['name' => 'Electrical Engineering']);

        /* ---------------- 2. ADMIN (unique: email) ---------------- */
        User::firstOrCreate(
            ['email' => 'admin@123.com'],
            [
                'name'      => 'HSBTE Admin',
                'password'  => 'password',      // auto-hashed by the model's cast
                'role'      => 'admin',
                'phone'     => '9000000001',
                'is_active' => true,
            ]
        );

        /* ---------------- 3. TRAINER (unique: email) ---------------- */
        $trainer = User::firstOrCreate(
            ['email' => 'trainer@123.com'],
            [
                'name'          => 'Gurdeep',
                'password'      => 'password',
                'role'          => 'trainer',
                'phone'         => '9000000002',
                'is_active'     => true,
                'department_id' => $cse->id,
                'designation'   => 'Head of Department, CSE',
                'qualification' => 'Computer Science',
            ]
        );

        /* ---------------- 4. STUDENT (for testing) (unique: email) ---------------- */
        User::firstOrCreate(
            ['email' => 'student@123.com'],
            [
                'name'          => 'Test Student',
                'password'      => 'password',
                'role'          => 'student',
                'phone'         => '9000000003',
                'is_active'     => true,
                'enrollment_no' => 'HSBTE24CS001',
                'institute'     => 'Govt Polytechnic, Ambala',
                'semester'      => '3rd',
                'department_id' => $cse->id,
            ]
        );

        /* ---------------- 5. SAMPLE COURSE (unique: slug) ---------------- */
        $course = Course::firstOrCreate(
            ['slug' => 'cyber-security-awareness'],
            [
                'department_id'  => $cse->id,
                'trainer_id'     => $trainer->id,
                'title'          => 'Cyber Security Awareness',
                'description'    => 'Learn the fundamentals of staying safe online — passwords, phishing, safe browsing, and protecting your digital identity. Free for all Haryana polytechnic students.',
                'thumbnail'      => null,
                'duration_weeks' => 8,
                'status'         => 'published',
                'is_paid'        => false,
                'price'          => 0,
            ]
        );

        /* ---------------- 6. MODULES + LESSONS (unique: course_id + title / module_id + title) ---------------- */
        $week1 = Module::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Week 1: Getting Started'],
            ['sort_order' => 1]
        );

        Lesson::firstOrCreate(
            ['module_id' => $week1->id, 'title' => 'What is Cyber Security?'],
            [
                'type'                   => 'video',
                'duration_minutes'       => 12,
                'sort_order'             => 1,
                'video_path'             => null,
                'video_duration_seconds' => 720,
            ]
        );
        Lesson::firstOrCreate(
            ['module_id' => $week1->id, 'title' => 'Course Handbook (PDF)'],
            [
                'type'             => 'pdf',
                'duration_minutes' => 5,
                'sort_order'       => 2,
                'file_path'        => null,
            ]
        );

        $week2 = Module::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Week 2: Passwords & Phishing'],
            ['sort_order' => 2]
        );

        Lesson::firstOrCreate(
            ['module_id' => $week2->id, 'title' => 'Creating Strong Passwords'],
            [
                'type'                   => 'video',
                'duration_minutes'       => 15,
                'sort_order'             => 1,
                'video_path'             => null,
                'video_duration_seconds' => 900,
            ]
        );
        Lesson::firstOrCreate(
            ['module_id' => $week2->id, 'title' => 'Spotting Phishing Emails'],
            [
                'type'                   => 'video',
                'duration_minutes'       => 10,
                'sort_order'             => 2,
                'video_path'             => null,
                'video_duration_seconds' => 600,
            ]
        );

        /* ---------------- 7. ANNOUNCEMENTS (unique: title) ---------------- */
        Announcement::firstOrCreate(
            ['title' => 'Cyber Security Awareness programme registrations open'],
            [
                'body'         => 'Last date to register: 15 August 2026. Free for all polytechnic students.',
                'is_active'    => true,
                'published_at' => now(),
            ]
        );
        Announcement::firstOrCreate(
            ['title' => 'AI & Emerging Tech course starting soon'],
            [
                'body'         => 'Registrations close 1 September 2026. Limited seats available.',
                'is_active'    => true,
                'published_at' => now()->subDay(),
            ]
        );
        Announcement::firstOrCreate(
            ['title' => 'Digital Governance workshop announced'],
            [
                'body'         => 'Online mode. Register before 10 September 2026.',
                'is_active'    => true,
                'published_at' => now()->subDays(2),
            ]
        );
    }
}
