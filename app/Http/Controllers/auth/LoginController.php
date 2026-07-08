<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the STUDENT login page
    public function showStudent()
    {
        return view('auth.login', ['role' => 'Student']);
    }

    // Show the TRAINER login page (same blade, different $role)
    public function showTrainer()
    {
        return view('auth.login', ['role' => 'Trainer']);
    }

    // Handle a login attempt
    public function login(Request $request)
    {
        // 1. Validate input server-side
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // 2. Attempt auth — hash-safe + timing-safe, handled by Laravel
        if (Auth::attempt($credentials, $remember)) {

            // 3. Regenerate session ID — blocks session-fixation attacks
            $request->session()->regenerate();

            $user = Auth::user();

            // 4. Block deactivated accounts
            if (! $user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact the administrator.',
                ])->onlyInput('email');
            }

            // 5. Send them to the right dashboard for their role
            return redirect()->intended($this->redirectPath($user->role));
        }

        // 6. Failed — generic message (never reveal which field was wrong)
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email'); // password is NEVER repopulated
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();      // kill the session data
        $request->session()->regenerateToken(); // fresh CSRF token

        return redirect('/');
    }

    private function redirectPath(string $role): string
    {
        return match ($role) {
            'admin'   => route('admin.dashboard'),
            'trainer' => route('trainer.dashboard'),
            default   => route('student.dashboard'),
        };
    }
}