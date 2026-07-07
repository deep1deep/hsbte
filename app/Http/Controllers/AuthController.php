<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Home Page
    public function home()
    {
        return view('home');
    }

    // Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/student/dashboard');
        }

        return back()->with('error', 'Invalid Email or Password');
    }

    // Logout
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}