@extends('layouts.app')

@section('title', 'HSBTE Training Portal')

@section('content')

<!-- Announcement Bar -->

<div class="marquee-container">
    <div class="marquee-label">📢 Latest Updates</div>
    <div class="marquee-track">
        <div class="ticker">
            <span>AI & Emerging Technologies Training registrations are now open.</span>
            <span class="dot">•</span>
            <span>Cyber Security Awareness Programme starts from 15 August.</span>
            <span class="dot">•</span>
            <span>Students can now download course completion certificates online.</span>
            <span class="dot">•</span>
            <span>Trainer onboarding portal is now live.</span>
            <span class="dot">•</span>

            <!-- Duplicate for seamless loop -->
            <span>AI & Emerging Technologies Training registrations are now open.</span>
            <span class="dot">•</span>
            <span>Cyber Security Awareness Programme starts from 15 August.</span>
            <span class="dot">•</span>
            <span>Students can now download course completion certificates online.</span>
            <span class="dot">•</span>
            <span>Trainer onboarding portal is now live.</span>
            <span class="dot">•</span>
        </div>
    </div>
</div>
<!-- Announcement end -->




<!-- ================= HERO SLIDER ================= -->

<section class="hero-slider">

<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">

<div class="carousel-inner">

<!-- ================= Slide 1 ================= -->

<div class="carousel-item active">

<div class="hero-slide">

<img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1800&auto=format&fit=crop"
class="hero-bg">

<div class="hero-overlay"></div>

<div class="container">

<div class="hero-content">

<span class="hero-tag">

Government of Haryana

</span>

<h1>

HSBTE Training Portal

</h1>

<h2>

Empowering Technical Education
Through Digital Learning

</h2>

<p>

Government Learning Management System for Students,
Teachers and Technical Institutions.

</p>

<div class="mt-4">

<a href="#" class="btn btn-warning btn-lg">

Explore Courses

</a>

<a href="#" class="btn btn-light btn-lg ms-2">

Student Login

</a>

</div>

</div>

</div>

</div>

</div>

<!-- ================= Slide 2 ================= -->

<div class="carousel-item">

<div class="hero-slide">

<img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1800&auto=format&fit=crop"
class="hero-bg">

<div class="hero-overlay"></div>

<div class="container">

<div class="hero-content">

<span class="hero-tag">

Digital Skill Development

</span>

<h1>

Future Ready Learning

</h1>

<h2>

Cyber Security
Artificial Intelligence
Digital Governance

</h2>

<p>

Interactive video lectures,
study material,
assessments and certification.

</p>

<div class="mt-4">

<a href="#" class="btn btn-warning btn-lg">

Browse Courses

</a>

</div>

</div>

</div>

</div>

</div>

<!-- ================= Slide 3 ================= -->

<div class="carousel-item">

<div class="hero-slide">

<img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1800&auto=format&fit=crop"
class="hero-bg">

<div class="hero-overlay"></div>

<div class="container">

<div class="hero-content">

<span class="hero-tag">

Learn Anywhere

</span>

<h1>

Any Device

</h1>

<h2>

Start Learning Today

</h2>

<p>

Watch videos,
download notes,
track progress
and earn certificates.

</p>

<div class="mt-4">

<a href="#" class="btn btn-warning btn-lg">

Get Started

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<button class="carousel-control-prev"
type="button"
data-bs-target="#heroCarousel"
data-bs-slide="prev">

<span class="carousel-control-prev-icon"></span>

</button>

<button class="carousel-control-next"
type="button"
data-bs-target="#heroCarousel"
data-bs-slide="next">

<span class="carousel-control-next-icon"></span>

</button>



</div>

</section>

@endsection