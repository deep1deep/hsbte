<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Show Login Page
    public function login()
    {
        return view('auth.login');
    }
}