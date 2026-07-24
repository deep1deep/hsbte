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
     * Send the reset link.
     *
     * Note: the response is ALWAYS the same — whether or not the email is registered.
     * Otherwise anyone could use this page to find out which emails are registered on
     * this portal (user enumeration). The login page follows the same approach —
     * "These credentials do not match our records."
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

    // Reset form (opened via the link sent by email)
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Set the new password
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
                // The User model's 'hashed' cast will hash this automatically
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

        // token expired/invalid, or the email did not match
        return back()->withErrors([
            'email' => 'This password reset link is invalid or has expired. Please request a new one.',
        ]);
    }
}
