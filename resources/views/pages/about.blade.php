@extends('layouts.app')

@section('title', 'About Us - ' . config('portal.name'))

@section('content')

<section class="info-hero">
    <div class="container">
        <div class="info-hero-inner">
            <div class="info-hero-emblem">
                <img src="{{ asset('images/HSBTEb2.png') }}" alt="Emblem of the Government of Haryana">
            </div>
            <div>
                <span class="info-hero-kicker">Government of Haryana</span>
                <h1>About Us</h1>
                <p class="info-hero-sub">
                    The official training and certification portal of the {{ config('portal.org') }}.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container" style="max-width:960px;">
        <div class="row g-4 align-items-center">

            <div class="col-lg-7">
                <span class="info-eyebrow">Who We Are</span>
                <h2 class="h4 info-section-title mb-3">What this portal is</h2>
                <p>
                    The {{ config('portal.name') }} is an online learning platform of the
                    {{ config('portal.org') }}. It offers free, certified training programmes
                    to students of polytechnic and technical institutions across Haryana.
                </p>
                <p class="mb-0">
                    Courses are prepared and delivered by departmental trainers. Every course
                    is free of cost, and students who complete a course receive a certificate
                    that can be verified online by employers and institutions.
                </p>
            </div>

            <div class="col-lg-5">
                <div class="board-card">
                    <img src="{{ asset('images/HSBTEb2.png') }}" alt="">
                    <h3>{{ config('portal.org') }}</h3>
                    <p class="board-tag">Government of Haryana</p>
                    <ul class="board-facts">
                        <li><i class="bi bi-mortarboard-fill"></i> Free, certified training programmes</li>
                        <li><i class="bi bi-patch-check-fill"></i> Certificates verifiable online</li>
                        <li><i class="bi bi-people-fill"></i> Open to technical students of Haryana</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="section-pad section-alt">
    <div class="container" style="max-width:960px;">
        <div class="text-center mb-4">
            <span class="info-eyebrow">Simple Process</span>
            <h2 class="h4 info-section-title mb-0">How it works</h2>
        </div>
        <div class="row g-3">
            @foreach([
                ['bi-person-plus', 'Register', 'Create a student account with your enrolment and department details.'],
                ['bi-journal-bookmark', 'Enrol', 'Browse the course catalogue and enrol in any published programme.'],
                ['bi-play-circle', 'Learn', 'Work through video lessons and downloadable study material at your own pace.'],
                ['bi-award', 'Get certified', 'Complete every lesson to receive your certificate.'],
            ] as $i => [$icon, $heading, $text])
                <div class="col-md-6 col-lg-3">
                    <div class="about-step">
                        <span class="about-step-num">{{ sprintf('%02d', $i + 1) }}</span>
                        <div class="about-step-icon"><i class="bi {{ $icon }}"></i></div>
                        <h3>{{ $heading }}</h3>
                        <p>{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container" style="max-width:960px;">
        <div class="row g-3">

            <div class="col-md-6">
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="bi bi-person-check"></i></div>
                    <h2 class="h6">Who can use it</h2>
                    <p>
                        Registration is open to students of technical institutions in Haryana.
                        Trainer accounts are created by the portal administrator; trainers
                        cannot self-register.
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="bi bi-patch-check"></i></div>
                    <h2 class="h6">Verifying a certificate</h2>
                    <p>
                        Any employer or institution can confirm a certificate is genuine without
                        logging in. Enter the certificate number on the
                        <a href="{{ route('certificate.verify') }}">certificate verification page</a>
                        to see the student's name, the course and the date of issue.
                    </p>
                </div>
            </div>

        </div>

        <p class="text-muted small mt-5 mb-0">
            Content last reviewed: {{ config('portal.last_reviewed') }}
        </p>
    </div>
</section>

@endsection
