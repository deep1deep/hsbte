@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('portal.name'))

@section('content')

@include('partials.page-hero', [
    'title'    => 'Privacy Policy',
    'subtitle' => 'How we handle your personal information',
])

<section class="section-pad">
    <div class="container" style="max-width:820px;">

        <h2 class="h4 mb-3">Information we collect</h2>
        <p>When you register as a student, we collect:</p>
        <ul class="text-muted">
            <li>Your name, email address and mobile number</li>
            <li>Your enrolment number, institution, department and semester</li>
            <li>Your Aadhaar number, used once to verify you are a real, unique applicant</li>
            <li>A password, which is stored only in encrypted (hashed) form</li>
        </ul>

        <div class="admin-card my-4" style="border-left:4px solid #0d2a5c;">
            <div class="admin-card-body">
                <h3 class="h6 mb-2">
                    <i class="bi bi-shield-lock"></i> How your Aadhaar number is handled
                </h3>
                <p class="text-muted small mb-0">
                    Your Aadhaar number is <strong>never stored</strong> on this portal.
                    When you register, the number is converted into a one-way cryptographic
                    hash and only that hash is saved. A hash cannot be reversed back into
                    your Aadhaar number. It is used solely to ensure the same person does
                    not register twice, and it is never displayed, exported or shared.
                </p>
            </div>
        </div>

        <h2 class="h4 mt-5 mb-3">Information created as you use the portal</h2>
        <ul class="text-muted">
            <li>Which courses you enrol in, and which lessons you have completed</li>
            <li>Certificates issued to you, including the certificate number and issue date</li>
            <li>A session cookie that keeps you logged in while you use the site</li>
        </ul>

        <h2 class="h4 mt-5 mb-3">How your information is used</h2>
        <p class="text-muted">
            Your information is used to run the portal — to give you access to courses,
            record your progress, issue your certificate, and allow employers to verify
            that certificate. It is also used in aggregate (totals only, never individual
            records) to report on training programmes.
        </p>

        <h2 class="h4 mt-5 mb-3">What is shown publicly</h2>
        <p class="text-muted">
            The certificate verification page is public and does not require a login.
            When a correct certificate number is entered, it displays the student's name,
            the course name and the date of issue — so that an employer can confirm the
            certificate is genuine. No other personal information is shown, and
            certificates cannot be searched or listed by name.
        </p>

        <h2 class="h4 mt-5 mb-3">Sharing</h2>
        <p class="text-muted">
            Your personal information is not sold, rented or shared with any commercial
            organisation. It may be shared with the {{ config('portal.org') }} and its
            authorised departments where required for administration of training
            programmes, or where disclosure is required by law.
        </p>

        <h2 class="h4 mt-5 mb-3">Security</h2>
        <p class="text-muted">
            Passwords are stored using one-way hashing and are never readable by anyone,
            including administrators. Access to student records is restricted by role —
            trainers can only see students enrolled in their own courses.
        </p>

        <h2 class="h4 mt-5 mb-3">Your rights</h2>
        <p class="text-muted">
            You may request a copy of the information we hold about you, ask for it to
            be corrected, or ask for your account to be closed. Write to
            <a href="mailto:{{ config('portal.contact.email') }}">{{ config('portal.contact.email') }}</a>
            from your registered email address.
        </p>

        <h2 class="h4 mt-5 mb-3">Changes to this policy</h2>
        <p class="text-muted">
            If this policy changes, the revised version will be published on this page
            with an updated review date.
        </p>

        <p class="text-muted small mt-5 mb-0">
            Content last reviewed: {{ config('portal.last_reviewed') }}
        </p>

    </div>
</section>

@endsection
