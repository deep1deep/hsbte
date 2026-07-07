@extends('layouts.app')

@section('title', 'HSBTE Training Portal')

@section('content')

<!-- ================= ANNOUNCEMENT BAR ================= -->
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

<!-- ================= HERO SLIDER ================= -->
<section class="hero-slider">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-inner">

            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div class="hero-slide">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1800&auto=format&fit=crop" class="hero-bg" alt="Students learning together">
                    <div class="hero-overlay"></div>
                    <div class="container">
                        <div class="hero-content">
                            <span class="hero-tag">Government of Haryana</span>
                            <h1>HSBTE Training Portal</h1>
                            <h2>Empowering Technical Education Through Digital Learning</h2>
                            <p>Government Learning Management System for Students, Teachers and Technical Institutions.</p>
                            <div class="mt-4">
                                <a href="#featured-courses" class="btn btn-warning btn-lg">Explore Courses</a>
                                <a href="/login" class="btn btn-light btn-lg ms-2">Student Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <div class="hero-slide">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1800&auto=format&fit=crop" class="hero-bg" alt="Digital skill development">
                    <div class="hero-overlay"></div>
                    <div class="container">
                        <div class="hero-content">
                            <span class="hero-tag">Digital Skill Development</span>
                            <h1>Future Ready Learning</h1>
                            <h2>Cyber Security, Artificial Intelligence, Digital Governance</h2>
                            <p>Interactive video lectures, study material, assessments and certification.</p>
                            <div class="mt-4">
                                <a href="#featured-courses" class="btn btn-warning btn-lg">Browse Courses</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <div class="hero-slide">
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1800&auto=format&fit=crop" class="hero-bg" alt="Learn on any device">
                    <div class="hero-overlay"></div>
                    <div class="container">
                        <div class="hero-content">
                            <span class="hero-tag">Learn Anywhere</span>
                            <h1>Any Device</h1>
                            <h2>Start Learning Today</h2>
                            <p>Watch videos, download notes, track progress and earn certificates.</p>
                            <div class="mt-4">
                                <a href="#" class="btn btn-warning btn-lg">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- ================= FEATURE STRIP ================= -->
<section class="feature-strip">
    <div class="container">
        <div class="row g-0">
            <div class="col-6 col-lg-3">
                <div class="feature-item">
                    <i class="bi bi-laptop"></i>
                    <h6>Free Courses</h6>
                    <p>Learn at your own pace</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="feature-item">
                    <i class="bi bi-patch-check"></i>
                    <h6>Govt. Certificates</h6>
                    <p>Verified & downloadable</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="feature-item">
                    <i class="bi bi-phone"></i>
                    <h6>Any Device</h6>
                    <p>Mobile, tablet or desktop</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="feature-item feature-item-last">
                    <i class="bi bi-briefcase"></i>
                    <h6>Job Ready</h6>
                    <p>Placement</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= NOTICES + QUICK LINKS ================= -->
<section class="section-pad">
    <div class="container">
        <div class="row g-4">

            <!-- Notice Board -->
            <div class="col-lg-7">
                <div class="notice-board">
                    <div class="notice-head">
                        <span><i class="bi bi-megaphone-fill me-2"></i>Notices & Circulars</span>
                        <a href="#">View All</a>
                    </div>
                    <ul class="notice-list">
                        <li>
                            <a href="#">AI & Emerging Technologies Training registrations are now open</a>
                            <span class="badge-new">NEW</span>
                        </li>
                        <li>
                            <a href="#">Cyber Security Awareness Programme starts from 15 August</a>
                        </li>
                        <li>
                            <a href="#">Students can now download course completion certificates online</a>
                        </li>
                        <li>
                            <a href="#">Trainer onboarding portal is now live</a>
                        </li>
                        <li>
                            <a href="#">Semester assessment schedule released for all departments</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-5">
                <div class="d-flex flex-column gap-3 h-100">
                    <a href="/register" class="quick-card">
                        <i class="bi bi-person-plus-fill"></i>
                        <div>
                            <h6>Student Registration</h6>
                            <p>Create your free account and start learning</p>
                        </div>
                        <i class="bi bi-chevron-right quick-arrow"></i>
                    </a>
                    <a href="#" class="quick-card">
                        <i class="bi bi-patch-check-fill"></i>
                        <div>
                            <h6>Verify Certificate</h6>
                            <p>Check the authenticity of any certificate ID</p>
                        </div>
                        <i class="bi bi-chevron-right quick-arrow"></i>
                    </a>
                    <a href="/trainer/login" class="quick-card">
                        <i class="bi bi-mortarboard-fill"></i>
                        <div>
                            <h6>Trainer Login</h6>
                            <p>Manage courses, videos and assessments</p>
                        </div>
                        <i class="bi bi-chevron-right quick-arrow"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= FEATURED COURSES ================= -->
<section class="section-pad section-alt" id="featured-courses">
    <div class="container">
        <div class="section-head">
            <div>
                <h3>Featured Courses</h3>
                <p>Most enrolled programmes this month</p>
            </div>
            <a href="#" class="btn btn-outline-navy">View All Courses</a>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="course-card">
                    <div class="course-thumb course-thumb-navy">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div class="course-body">
                        <span class="course-dept">Computer Science</span>
                        <h5>Cyber Security Awareness</h5>
                        <div class="course-meta">
                            <span><i class="bi bi-clock"></i> 8 weeks</span>
                            <span><i class="bi bi-people"></i> 2,340 enrolled</span>
                        </div>
                        <div class="course-foot">
                            <span class="course-free">FREE · Certificate</span>
                            <a href="#" class="btn btn-sm btn-navy">Enroll</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="course-card">
                    <div class="course-thumb course-thumb-purple">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <div class="course-body">
                        <span class="course-dept">Emerging Technologies</span>
                        <h5>AI & Emerging Technologies</h5>
                        <div class="course-meta">
                            <span><i class="bi bi-clock"></i> 6 weeks</span>
                            <span><i class="bi bi-people"></i> 1,890 enrolled</span>
                        </div>
                        <div class="course-foot">
                            <span class="course-free">FREE · Certificate</span>
                            <a href="#" class="btn btn-sm btn-navy">Enroll</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="course-card">
                    <div class="course-thumb course-thumb-teal">
                        <i class="bi bi-code-slash"></i>
                    </div>
                    <div class="course-body">
                        <span class="course-dept">Computer Science</span>
                        <h5>Web Development Fundamentals</h5>
                        <div class="course-meta">
                            <span><i class="bi bi-clock"></i> 10 weeks</span>
                            <span><i class="bi bi-people"></i> 3,105 enrolled</span>
                        </div>
                        <div class="course-foot">
                            <span class="course-free">FREE · Certificate</span>
                            <a href="#" class="btn btn-sm btn-navy">Enroll</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- ================= STATS + CTA ================= -->
<section class="cta-band">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="row text-center g-3">
                    <div class="col-3">
                        <div class="stat-num">10,000+</div>
                        <div class="stat-label">Students</div>
                    </div>
                    <div class="col-3">
                        <div class="stat-num">120+</div>
                        <div class="stat-label">Courses</div>
                    </div>
                    <div class="col-3">
                        <div class="stat-num">300+</div>
                        <div class="stat-label">Trainers</div>
                    </div>
                    <div class="col-3">
                        <div class="stat-num">40+</div>
                        <div class="stat-label">Institutes</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end text-center">
                <h4>Start your journey today</h4>
                <p>Free for all Haryana polytechnic students.</p>
                <a href="/register" class="btn btn-warning btn-lg">Create Free Account</a>
            </div>
        </div>
    </div>
</section>

<!-- ================= UPCOMING TRAINING PROGRAMMES ================= -->
<section class="section-pad">
    <div class="container">
        <div class="section-head">
            <div>
                <h3>Upcoming Training Programmes</h3>
                <p>Register before seats fill up</p>
            </div>
            <a href="#" class="btn btn-outline-navy">View Full Schedule</a>
        </div>

        <div class="row g-4">

            <!-- Training 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="training-card">
                    <div class="training-top">
                        <div class="training-date">
                            <span class="td-day">15</span>
                            <span class="td-month">AUG</span>
                        </div>
                        <div>
                            <span class="training-mode"><i class="bi bi-camera-video"></i> Online</span>
                            <h5>Cyber Security Awareness Programme</h5>
                        </div>
                    </div>
                    <ul class="training-info">
                        <li><i class="bi bi-calendar-check"></i> Registration closes: <strong>10 Aug 2026</strong></li>
                        <li><i class="bi bi-clock"></i> Duration: 8 weeks</li>
                        <li><i class="bi bi-people"></i> <span class="seats-left">Only 45 seats left</span></li>
                    </ul>
                    <div class="training-foot">
                        <span class="course-free">FREE · Certificate</span>
                        <a href="#" class="btn btn-sm btn-navy">Register Now</a>
                    </div>
                </div>
            </div>

            <!-- Training 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="training-card">
                    <div class="training-top">
                        <div class="training-date">
                            <span class="td-day">01</span>
                            <span class="td-month">SEP</span>
                        </div>
                        <div>
                            <span class="training-mode"><i class="bi bi-camera-video"></i> Online</span>
                            <h5>AI & Emerging Technologies Training</h5>
                        </div>
                    </div>
                    <ul class="training-info">
                        <li><i class="bi bi-calendar-check"></i> Registration closes: <strong>25 Aug 2026</strong></li>
                        <li><i class="bi bi-clock"></i> Duration: 6 weeks</li>
                        <li><i class="bi bi-people"></i> <span class="seats-open">Registrations open</span></li>
                    </ul>
                    <div class="training-foot">
                        <span class="course-free">FREE · Certificate</span>
                        <a href="#" class="btn btn-sm btn-navy">Register Now</a>
                    </div>
                </div>
            </div>

            <!-- Training 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="training-card">
                    <div class="training-top">
                        <div class="training-date">
                            <span class="td-day">10</span>
                            <span class="td-month">SEP</span>
                        </div>
                        <div>
                            <span class="training-mode"><i class="bi bi-geo-alt"></i> Hybrid</span>
                            <h5>Digital Governance Workshop</h5>
                        </div>
                    </div>
                    <ul class="training-info">
                        <li><i class="bi bi-calendar-check"></i> Registration closes: <strong>05 Sep 2026</strong></li>
                        <li><i class="bi bi-clock"></i> Duration: 2 weeks</li>
                        <li><i class="bi bi-people"></i> <span class="seats-open">Registrations open</span></li>
                    </ul>
                    <div class="training-foot">
                        <span class="course-free">FREE · Certificate</span>
                        <a href="#" class="btn btn-sm btn-navy">Register Now</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection