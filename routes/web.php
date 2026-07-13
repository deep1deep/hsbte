<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Trainer\TrainerController;

/* ---------------- PUBLIC PAGES ---------------- */
Route::view('/', 'home')->name('home');
Route::view('/courses', 'courses')->name('courses');
Route::view('/courses/cyber-security-awareness', 'course-detail')->name('course.detail');

/* ---------------- AUTH: REGISTER / LOGIN / LOGOUT ---------------- */
Route::get('/register',  [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');
Route::get('/login',         [LoginController::class, 'showStudent'])->name('login');
Route::get('/trainer/login', [LoginController::class, 'showTrainer'])->name('trainer.login');
Route::post('/login',        [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout',       [LoginController::class, 'logout'])->name('logout');

/* ---------------- STUDENT (abhi placeholder) ---------------- */
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::view('/student/dashboard', 'dashboards.student')->name('student.dashboard');
});

/* ---------------- ADMIN ---------------- */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/trainers', [AdminController::class, 'storeTrainer'])->name('admin.trainers.store');
});

/* ---------------- TRAINER ---------------- */
Route::middleware(['auth','role:trainer'])->group(function () {
    Route::get('/trainer/dashboard',                 [TrainerController::class, 'dashboard'])->name('trainer.dashboard');

    // course create / manage / edit
    Route::get('/trainer/courses/create',            [TrainerController::class, 'createCourse'])->name('trainer.courses.create');
    Route::post('/trainer/courses',                  [TrainerController::class, 'storeCourse'])->name('trainer.courses.store');
    Route::get('/trainer/courses/{course}/manage',   [TrainerController::class, 'manageCourse'])->name('trainer.courses.manage');
    Route::get('/trainer/courses/{course}/edit',     [TrainerController::class, 'editCourse'])->name('trainer.courses.edit');
    Route::patch('/trainer/courses/{course}',        [TrainerController::class, 'updateCourse'])->name('trainer.courses.update');
    Route::patch('/trainer/courses/{course}/publish',[TrainerController::class, 'togglePublish'])->name('trainer.courses.publish');

    // modules
    Route::post('/trainer/courses/{course}/modules', [TrainerController::class, 'storeModule'])->name('trainer.modules.store');
    Route::patch('/trainer/modules/{module}',        [TrainerController::class, 'updateModule'])->name('trainer.modules.update');
    Route::delete('/trainer/modules/{module}',       [TrainerController::class, 'destroyModule'])->name('trainer.modules.destroy');

    // lessons
    Route::post('/trainer/modules/{module}/lessons', [TrainerController::class, 'storeLesson'])->name('trainer.lessons.store');
    Route::patch('/trainer/lessons/{lesson}',        [TrainerController::class, 'updateLesson'])->name('trainer.lessons.update');
    Route::delete('/trainer/lessons/{lesson}',       [TrainerController::class, 'destroyLesson'])->name('trainer.lessons.destroy');
});