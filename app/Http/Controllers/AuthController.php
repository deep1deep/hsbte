<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login Request
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/student/dashboard');
            }
        }

        return back()->with('error', 'Invalid login details');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}