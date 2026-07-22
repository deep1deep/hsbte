@extends('layouts.app')

@section('title', 'Contact Us - ' . config('portal.name'))

@section('content')

@include('partials.page-hero', [
    'title'    => 'Contact Us',
    'subtitle' => 'Get in touch with the ' . config('portal.org_short') . ' training team',
])

<section class="section-pad">
    <div class="container" style="max-width:820px;">

        <div class="row g-3">

            <div class="col-md-6">
                <div class="admin-card h-100">
                    <div class="admin-card-body">
                        <i class="bi bi-geo-alt" style="font-size:22px;color:#0d2a5c;"></i>
                        <h2 class="h6 mt-2 mb-1">Address</h2>
                        <p class="text-muted small mb-0">
                            {{ config('portal.org') }}<br>
                            {{ config('portal.contact.address') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="admin-card h-100">
                    <div class="admin-card-body">
                        <i class="bi bi-envelope" style="font-size:22px;color:#0d2a5c;"></i>
                        <h2 class="h6 mt-2 mb-1">Email</h2>
                        <p class="text-muted small mb-0">
                            <a href="mailto:{{ config('portal.contact.email') }}">
                                {{ config('portal.contact.email') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="admin-card h-100">
                    <div class="admin-card-body">
                        <i class="bi bi-telephone" style="font-size:22px;color:#0d2a5c;"></i>
                        <h2 class="h6 mt-2 mb-1">Helpline</h2>
                        <p class="text-muted small mb-0">{{ config('portal.contact.phone') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="admin-card h-100">
                    <div class="admin-card-body">
                        <i class="bi bi-clock" style="font-size:22px;color:#0d2a5c;"></i>
                        <h2 class="h6 mt-2 mb-1">Office hours</h2>
                        <p class="text-muted small mb-0">{{ config('portal.contact.hours') }}</p>
                    </div>
                </div>
            </div>

        </div>

        <h2 class="h4 mt-5 mb-3">Before you write to us</h2>
        <p class="text-muted">
            Many common questions — enrolling in a course, video playback problems,
            certificates and password resets — are answered on the
            <a href="{{ route('help') }}">help page</a>.
        </p>

        <div class="admin-card mt-4">
            <div class="admin-card-body">
                <h2 class="h6 mb-2">When contacting us, please include</h2>
                <ul class="text-muted small mb-0">
                    <li>Your full name and enrolment number</li>
                    <li>Your institution and department</li>
                    <li>The course name, if your question is about a specific course</li>
                    <li>The certificate number, if your question is about a certificate</li>
                </ul>
            </div>
        </div>

        <p class="text-muted small mt-5 mb-0">
            Content last reviewed: {{ config('portal.last_reviewed') }}
        </p>

    </div>
</section>

@endsection
