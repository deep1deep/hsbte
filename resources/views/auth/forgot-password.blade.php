@extends('layouts.app')

@section('title', 'Forgot Password - HSBTE Training Portal')

@section('content')

<section class="auth-wrap">
    <div class="container">
        <div class="auth-card">

            <!-- Left brand panel -->
            <div class="auth-brand">
                <div class="brand-badge"></div>
                <h2>Forgot your password?</h2>
                <p>It happens. Enter your registered email address and we will send you a link to set a new one.</p>
                <ul class="auth-points">
                    <li><i class="bi bi-check-circle-fill"></i> The link is valid for 60 minutes</li>
                    <li><i class="bi bi-check-circle-fill"></i> Your courses and progress are safe</li>
                    <li><i class="bi bi-check-circle-fill"></i> Your certificates are not affected</li>
                </ul>
            </div>

            <!-- Right form panel -->
            <div class="auth-form">
                <h4>Reset your password</h4>
                <p class="auth-sub">We will email you a link to choose a new password</p>

                @if (session('success'))
                    <div class="alert alert-success py-2 small">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="email">Email address</label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="you@example.com" required autofocus>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-navy w-100">Send reset link</button>
                </form>

                <div class="auth-links">
                    Remembered it? <a href="{{ route('login') }}">Back to login</a>
                    <span class="mx-1">·</span>
                    <a href="{{ route('contact') }}">Need help?</a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
