<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Home Page
Route::get('/', [AuthController::class, 'home']);

// Login
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::get('/logout', [AuthController::class, 'logout']);

// Temporary Dashboards
Route::get('/admin/dashboard', function () {
    return "Admin Dashboard";
});

Route::get('/student/dashboard', function () {
    return "Student Dashboard";
});

Route::view('/courses', 'courses')->name('courses');
Route::view('/login', 'auth.login', ['role' => 'Student'])->name('login');
Route::view('/trainer/login', 'auth.login', ['role' => 'Trainer'])->name('trainer.login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/courses/cyber-security-awareness', 'course-detail')->name('course.detail');