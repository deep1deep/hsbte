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