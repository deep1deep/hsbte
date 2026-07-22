@extends('layouts.app')

@section('title', 'Terms of Use - ' . config('portal.name'))

@section('content')

@include('partials.page-hero', [
    'title'    => 'Terms of Use',
    'subtitle' => 'Conditions for using this portal',
])

<section class="section-pad">
    <div class="container" style="max-width:820px;">

        <h2 class="h4 mb-3">Acceptance</h2>
        <p class="text-muted">
            By registering for or using the {{ config('portal.name') }}, you agree to
            these terms. If you do not agree, please do not use the portal.
        </p>

        <h2 class="h4 mt-5 mb-3">Your account</h2>
        <ul class="text-muted">
            <li>You must provide accurate and complete information when registering.</li>
            <li>One person may hold only one student account.</li>
            <li>You are responsible for keeping your password confidential.</li>
            <li>You must not share your account or allow anyone else to use it.</li>
            <li>Accounts found to contain false information may be deactivated.</li>
        </ul>

        <h2 class="h4 mt-5 mb-3">Course material</h2>
        <p class="text-muted">
            All videos, documents and other course material on this portal are the
            property of the {{ config('portal.org') }} or its trainers. You may use
            them for your own learning. You may not download, re-upload, redistribute,
            sell or publish them elsewhere without written permission.
        </p>

        <h2 class="h4 mt-5 mb-3">Certificates</h2>
        <ul class="text-muted">
            <li>A certificate is issued only after every lesson in a course is completed.</li>
            <li>Some courses require the trainer to review and issue the certificate; in
                that case it will show as pending until the trainer has done so.</li>
            <li>Each certificate carries a unique number and can be verified publicly.</li>
            <li>Altering, forging or misrepresenting a certificate is a serious offence
                and may result in the certificate being cancelled and further action
                being taken.</li>
        </ul>

        <h2 class="h4 mt-5 mb-3">Acceptable use</h2>
        <p class="text-muted">You must not:</p>
        <ul class="text-muted">
            <li>Attempt to gain unauthorised access to any part of the portal, other
                accounts, or the systems it runs on</li>
            <li>Interfere with the normal operation of the portal, or place an
                unreasonable load on it</li>
            <li>Upload anything unlawful, offensive, or containing malicious code</li>
            <li>Use automated tools to copy content or create accounts</li>
        </ul>

        <h2 class="h4 mt-5 mb-3">Availability</h2>
        <p class="text-muted">
            We aim to keep the portal available at all times, but access may be
            interrupted for maintenance, upgrades or reasons beyond our control. No
            guarantee of uninterrupted availability is given.
        </p>

        <h2 class="h4 mt-5 mb-3">Changes</h2>
        <p class="text-muted">
            These terms may be updated from time to time. The current version is always
            the one published on this page.
        </p>

        <h2 class="h4 mt-5 mb-3">Contact</h2>
        <p class="text-muted">
            Questions about these terms may be sent to
            <a href="mailto:{{ config('portal.contact.email') }}">{{ config('portal.contact.email') }}</a>.
        </p>

        <p class="text-muted small mt-5 mb-0">
            Content last reviewed: {{ config('portal.last_reviewed') }}
        </p>

    </div>
</section>

@endsection
