<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// show login page
Route::get('/', [AuthController::class, 'showLogin']);

// process login
Route::post('/login', [AuthController::class, 'login']);

// logout
Route::get('/logout', [AuthController::class, 'logout']);

// dashboards (temporary)
Route::get('/admin/dashboard', function () {
    return "Admin Dashboard";
});

Route::get('/student/dashboard', function () {
    return "Student Dashboard";
});