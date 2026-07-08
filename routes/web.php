<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\RegisterController;
/* ---------------- PUBLIC PAGES ---------------- */
Route::view('/', 'home')->name('home');
Route::view('/courses', 'courses')->name('courses');
Route::view('/courses/cyber-security-awareness', 'course-detail')->name('course.detail');


/* ---------------- AUTH: LOGIN / LOGOUT ---------------- */
Route::get('/login',         [LoginController::class, 'showStudent'])->name('login');
Route::get('/trainer/login', [LoginController::class, 'showTrainer'])->name('trainer.login');
Route::post('/login',        [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout',       [LoginController::class, 'logout'])->name('logout');

/* ---------------- PROTECTED DASHBOARDS ---------------- */
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::view('/student/dashboard', 'dashboards.student')->name('student.dashboard');
});
Route::middleware(['auth', 'role:trainer'])->group(function () {
    Route::view('/trainer/dashboard', 'dashboards.trainer')->name('trainer.dashboard');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('/admin/dashboard', 'dashboards.admin')->name('admin.dashboard');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/trainers', [AdminController::class, 'storeTrainer'])->name('admin.trainers.store');
});
Route::get('/register',  [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');