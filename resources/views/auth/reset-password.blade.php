@extends('layouts.app')

@section('title', 'Set a New Password - HSBTE Training Portal')

@section('content')

<section class="auth-wrap">
    <div class="container">
        <div class="auth-card">

            <!-- Left brand panel -->
            <div class="auth-brand">
                <div class="brand-badge"></div>
                <h2>Choose a new password</h2>
                <p>Pick something you have not used before, and keep it to yourself.</p>
                <ul class="auth-points">
                    <li><i class="bi bi-check-circle-fill"></i> At least 8 characters</li>
                    <li><i class="bi bi-check-circle-fill"></i> Never share it with anyone</li>
                    <li><i class="bi bi-check-circle-fill"></i> Staff will never ask for it</li>
                </ul>
            </div>

            <!-- Right form panel -->
            <div class="auth-form">
                <h4>Set new password</h4>
                <p class="auth-sub">Enter and confirm your new password</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label class="form-label" for="email">Email address</label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', $email) }}"
                                   class="form-control" placeholder="you@example.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">New password</label>
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password"
                                   class="form-control" placeholder="At least 8 characters"
                                   required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Confirm new password</label>
                        <div class="input-icon">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" placeholder="Type it again" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-navy w-100">Change password</button>
                </form>

                <div class="auth-links">
                    <a href="{{ route('login') }}">Back to login</a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
