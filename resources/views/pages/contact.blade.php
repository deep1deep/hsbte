@extends('layouts.app')

@section('title', 'Contact Us - ' . config('portal.name'))

@section('content')

<section class="info-hero">
    <div class="container">
        <div class="info-hero-inner">
            <div class="info-hero-emblem">
                <img src="{{ asset('images/HSBTEb2.png') }}" alt="Emblem of the Government of Haryana">
            </div>
            <div>
                <span class="info-hero-kicker">We're here to help</span>
                <h1>Contact Us</h1>
                <p class="info-hero-sub">
                    Get in touch with the {{ config('portal.org_short') }} training team.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container" style="max-width:960px;">

        <div class="row g-3">

            <div class="col-md-6 col-lg-3">
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="bi bi-geo-alt"></i></div>
                    <h2 class="h6">Address</h2>
                    <p>
                        {{ config('portal.org') }}<br>
                        {{ config('portal.contact.address') }}
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="bi bi-envelope"></i></div>
                    <h2 class="h6">Email</h2>
                    <p>
                        <a href="mailto:{{ config('portal.contact.email') }}">
                            {{ config('portal.contact.email') }}
                        </a>
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="bi bi-telephone"></i></div>
                    <h2 class="h6">Helpline</h2>
                    <p>{{ config('portal.contact.phone') }}</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="bi bi-clock"></i></div>
                    <h2 class="h6">Office hours</h2>
                    <p>{{ config('portal.contact.hours') }}</p>
                </div>
            </div>

        </div>

        <div class="row g-3 mt-4 align-items-stretch">

            <div class="col-lg-6">
                <div class="contact-panel h-100">
                    <span class="info-eyebrow">Quick Answers</span>
                    <h2>Before you write to us</h2>
                    <p class="text-muted mb-3" style="font-size:.9rem;">
                        Many common questions — enrolling in a course, video playback problems,
                        certificates and password resets — are already answered on the help page.
                    </p>
                    <a href="{{ route('help') }}" class="btn btn-navy btn-sm">
                        <i class="bi bi-question-circle me-1"></i> Visit the help page
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-panel h-100">
                    <span class="info-eyebrow">Faster Support</span>
                    <h2>When contacting us, please include</h2>
                    <ul class="contact-checklist">
                        <li><i class="bi bi-check-circle-fill"></i> Your full name and enrolment number</li>
                        <li><i class="bi bi-check-circle-fill"></i> Your institution and department</li>
                        <li><i class="bi bi-check-circle-fill"></i> The course name, if your question is about a specific course</li>
                        <li><i class="bi bi-check-circle-fill"></i> The certificate number, if your question is about a certificate</li>
                    </ul>
                </div>
            </div>

        </div>

        <p class="text-muted small mt-5 mb-0">
            Content last reviewed: {{ config('portal.last_reviewed') }}
        </p>

    </div>
</section>

@endsection
