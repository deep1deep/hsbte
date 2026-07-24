<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Trainer\TrainerController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CertificateController;

/* ---------------- PUBLIC PAGES ---------------- */
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/courses', [CourseController::class, 'index'])->name('courses');

// dynamic course detail — any published course opens by its slug
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('course.detail');

// public certificate verify (no login — an employer/anyone can enter a number to check)
Route::get('/verify', [CertificateController::class, 'verify'])->name('certificate.verify');

// public notices
Route::get('/notices', [\App\Http\Controllers\NoticeController::class, 'index'])->name('notices');

/* ---------------- INFORMATION PAGES (expected for GIGW) ---------------- */
Route::controller(\App\Http\Controllers\PageController::class)->group(function () {
    Route::get('/about',         'about')->name('about');
    Route::get('/contact',       'contact')->name('contact');
    Route::get('/privacy',       'privacy')->name('privacy');
    Route::get('/terms',         'terms')->name('terms');
    Route::get('/accessibility', 'accessibility')->name('accessibility');
    // Help page hidden — content is covered by the Contact Us page.
    // Route::get('/help',          'help')->name('help');
});

/* ---------------- AUTH: REGISTER / LOGIN / LOGOUT ---------------- */
// throttle only on POST — no limit on opening the page.
// The limits are intentionally loose: a normal user will never hit them,
// automated password-guessing stops immediately.
Route::get('/register',  [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('throttle:10,1')->name('register.attempt');

Route::get('/login',         [LoginController::class, 'showStudent'])->name('login');
Route::get('/trainer/login', [LoginController::class, 'showTrainer'])->name('trainer.login');
Route::post('/login',        [LoginController::class, 'login'])
    ->middleware('throttle:20,1')->name('login.attempt');

Route::post('/logout',       [LoginController::class, 'logout'])->name('logout');

/* ---------------- PASSWORD RESET ---------------- */
// Route names match Laravel's conventions — the reset email link
// is built from route('password.reset'), so don't change the names.
Route::controller(\App\Http\Controllers\Auth\PasswordResetController::class)->group(function () {
    Route::get('/forgot-password',  'showLinkRequestForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetLink')
        ->middleware('throttle:6,1')->name('password.email');   // to prevent email bombing

    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('/reset-password',        'reset')
        ->middleware('throttle:6,1')->name('password.update');
});

/* ---------------- ENROLL (auth only — sends a guest to login, then brings them back to the course) ---------------- */
Route::post('/courses/{course}/enroll', [StudentController::class, 'enroll'])
    ->middleware('auth')->name('student.enroll');

/* ---------------- STUDENT ---------------- */
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard',                   [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/courses/{course}',            [StudentController::class, 'showCourse'])->name('student.course.show');
    Route::post('/student/lessons/{lesson}/complete',  [StudentController::class, 'markComplete'])->name('student.lessons.complete');
    Route::post('/student/lessons/{lesson}/note',       [StudentController::class, 'saveNote'])->name('student.lessons.note');
    Route::post('/student/courses/{course}/review',      [StudentController::class, 'storeReview'])->name('student.course.review');

    // profile (own details + password)
    Route::get('/student/profile',          [StudentController::class, 'profile'])->name('student.profile');
    Route::patch('/student/profile',        [StudentController::class, 'updateProfile'])->name('student.profile.update');
    Route::patch('/student/profile/password',[StudentController::class, 'updatePassword'])->name('student.profile.password');

    // certificate download (only their own)
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificate.download');
});

/* ---------------- ADMIN ---------------- */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/trainers', [AdminController::class, 'storeTrainer'])->name('admin.trainers.store');

    // trainer management (edit / enable-disable / reset password)
    Route::patch('/admin/trainers/{trainer}',          [AdminController::class, 'updateTrainer'])->name('admin.trainers.update');
    Route::patch('/admin/trainers/{trainer}/toggle',   [AdminController::class, 'toggleTrainer'])->name('admin.trainers.toggle');
    Route::patch('/admin/trainers/{trainer}/password', [AdminController::class, 'resetTrainerPassword'])->name('admin.trainers.password');

    // students (search / filter / export)
    Route::get('/admin/students',        [AdminController::class, 'students'])->name('admin.students');
    Route::get('/admin/students/export', [AdminController::class, 'exportStudents'])->name('admin.students.export');

    // course oversight
    Route::get('/admin/courses',                 [AdminController::class, 'courses'])->name('admin.courses');
    Route::patch('/admin/courses/{course}/status',[AdminController::class, 'updateCourseStatus'])->name('admin.courses.status');

    // notices / announcements CRUD — ADMIN ONLY
    Route::get('/admin/announcements',                  [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('admin.announcements');
    Route::post('/admin/announcements',                 [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::patch('/admin/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/admin/announcements/{announcement}',[\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
});

/* ---------------- TRAINER ---------------- */
Route::middleware(['auth','role:trainer'])->group(function () {
    Route::get('/trainer/dashboard',                 [TrainerController::class, 'dashboard'])->name('trainer.dashboard');

    // course create / manage / edit
    Route::get('/trainer/courses/create',            [TrainerController::class, 'createCourse'])->name('trainer.courses.create');
    Route::post('/trainer/courses',                  [TrainerController::class, 'storeCourse'])->name('trainer.courses.store');
    Route::get('/trainer/courses/{course}/manage',   [TrainerController::class, 'manageCourse'])->name('trainer.courses.manage');
    Route::get('/trainer/courses/{course}/students', [TrainerController::class, 'courseStudents'])->name('trainer.courses.students');
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
    Route::get('/trainer/certificate-design',  [TrainerController::class, 'certificateDesign'])->name('trainer.certificate.design');
    Route::post('/trainer/certificate-design', [TrainerController::class, 'saveCertificateDesign'])->name('trainer.certificate.design.save');
    // certificates (manual upload)
    Route::get('/trainer/certificates',                        [TrainerController::class, 'certificates'])->name('trainer.certificates');
    Route::post('/trainer/certificates/{certificate}/upload',  [TrainerController::class, 'uploadCertificate'])->name('trainer.certificates.upload');
});