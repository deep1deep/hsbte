@extends('layouts.app')

@section('title', 'About Us - ' . config('portal.name'))

@section('content')

@include('partials.page-hero', [
    'title'    => 'About Us',
    'subtitle' => 'About the ' . config('portal.name'),
])

<section class="section-pad">
    <div class="container" style="max-width:820px;">

        <h2 class="h4 mb-3">What this portal is</h2>
        <p>
            The {{ config('portal.name') }} is an online learning platform of the
            {{ config('portal.org') }}. It offers free, certified training programmes
            to students of polytechnic and technical institutions across Haryana.
        </p>
        <p>
            Courses are prepared and delivered by departmental trainers. Every course
            is free of cost, and students who complete a course receive a certificate
            that can be verified online by employers and institutions.
        </p>

        <h2 class="h4 mt-5 mb-3">How it works</h2>
        <div class="row g-3">
            @foreach([
                ['bi-person-plus', 'Register', 'Create a student account with your enrolment and department details.'],
                ['bi-journal-bookmark', 'Enrol', 'Browse the course catalogue and enrol in any published programme.'],
                ['bi-play-circle', 'Learn', 'Work through video lessons and downloadable study material at your own pace.'],
                ['bi-award', 'Get certified', 'Complete every lesson to receive your certificate.'],
            ] as [$icon, $heading, $text])
                <div class="col-md-6">
                    <div class="admin-card h-100">
                        <div class="admin-card-body">
                            <i class="bi {{ $icon }}" style="font-size:22px;color:#0d2a5c;"></i>
                            <h3 class="h6 mt-2 mb-1">{{ $heading }}</h3>
                            <p class="text-muted small mb-0">{{ $text }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="h4 mt-5 mb-3">Who can use it</h2>
        <p>
            Registration is open to students of technical institutions in Haryana.
            Trainer accounts are created by the portal administrator; trainers cannot
            self-register.
        </p>

        <h2 class="h4 mt-5 mb-3">Verifying a certificate</h2>
        <p>
            Any employer or institution can confirm a certificate is genuine without
            logging in. Enter the certificate number on the
            <a href="{{ route('certificate.verify') }}">certificate verification page</a>
            to see the student's name, the course and the date of issue.
        </p>

        <p class="text-muted small mt-5 mb-0">
            Content last reviewed: {{ config('portal.last_reviewed') }}
        </p>

    </div>
</section>

@endsection
