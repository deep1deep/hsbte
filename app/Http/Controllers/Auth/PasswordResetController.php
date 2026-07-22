<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // "Forgot password" form
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Reset link bhejo.
     *
     * Note: response HAMESHA same rehta hai — chahe email registered ho ya na ho.
     * Warna koi bhi is page se pata kar sakta hai ki kaun-kaunsi email is portal
     * pe registered hai (user enumeration). Login page bhi yahi approach follow
     * karta hai — "These credentials do not match our records."
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(
            ['email' => ['required', 'email']],
            ['email.required' => 'Please enter your email address.']
        );

        Password::sendResetLink($request->only('email'));

        return back()->with('success',
            'If that email address is registered with us, a password reset link has been sent to it. '
            . 'Please check your inbox, and your spam folder. The link is valid for 60 minutes.'
        );
    }

    // Reset form (email me bheje gaye link se khulta hai)
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Naya password set karo
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min'       => 'Your new password must be at least 8 characters.',
            'password.confirmed' => 'The two passwords do not match.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // User model ka 'hashed' cast isse apne aap hash kar dega
                $user->forceFill(['password' => $password])
                     ->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Your password has been changed. Please log in with your new password.');
        }

        // token expire/galat, ya email match nahi hui
        return back()->withErrors([
            'email' => 'This password reset link is invalid or has expired. Please request a new one.',
        ]);
    }
}
