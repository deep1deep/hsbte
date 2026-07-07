@extends('layouts.app')

@section('title', 'Cyber Security Awareness - HSBTE Training Portal')

@section('content')

<!-- ================= COURSE BANNER ================= -->
<section class="cd-banner">
    <div class="container">
        <div class="cd-breadcrumb">Home / Courses / Cyber Security Awareness</div>

        <div class="cd-tags">
            <span class="cd-tag-dept">Computer Science</span>
            <span class="cd-tag-free">FREE</span>
        </div>

        <h1>Cyber Security Awareness</h1>
        <p>Learn to protect yourself and organisations from digital threats. Certificate on completion.</p>

        <div class="cd-stats">
            <span><i class="bi bi-clock"></i> 8 weeks</span>
            <span><i class="bi bi-people"></i> 2,340 enrolled</span>
            <span><i class="bi bi-collection-play"></i> 24 lessons</span>
            <span><i class="bi bi-person"></i> Dr. A. Kumar</span>
        </div>
    </div>
</section>

<!-- ================= COURSE BODY ================= -->
<section class="section-pad">
    <div class="container">
        <div class="row g-4">

            <!-- LEFT: content -->
            <div class="col-lg-8">

                <!-- What you'll learn -->
                <div class="cd-box mb-4">
                    <h5>What you'll learn</h5>
                    <div class="row g-2 cd-learn">
                        <div class="col-md-6"><i class="bi bi-check-circle-fill"></i> Password & account safety</div>
                        <div class="col-md-6"><i class="bi bi-check-circle-fill"></i> Phishing detection</div>
                        <div class="col-md-6"><i class="bi bi-check-circle-fill"></i> Safe browsing habits</div>
                        <div class="col-md-6"><i class="bi bi-check-circle-fill"></i> Data protection basics</div>
                        <div class="col-md-6"><i class="bi bi-check-circle-fill"></i> Social engineering awareness</div>
                        <div class="col-md-6"><i class="bi bi-check-circle-fill"></i> Reporting cyber crime</div>
                    </div>
                </div>

                <!-- Course content accordion -->
                <div class="cd-box p-0">
                    <div class="cd-content-head">
                        Course content <span>4 modules · 24 lessons · 6h 20m total</span>
                    </div>

                    <div class="accordion cd-accordion" id="courseContent">

                        <!-- Module 1 (open) -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#mod1">
                                    Week 1: Introduction to Cyber Security
                                    <span class="cd-mod-count">6 lessons</span>
                                </button>
                            </h2>
                            <div id="mod1" class="accordion-collapse collapse show" data-bs-parent="#courseContent">
                                <div class="accordion-body p-0">
                                    <ul class="cd-lessons">
                                        <li><span><i class="bi bi-play-circle"></i> What is cyber security?</span><span>12:40</span></li>
                                        <li><span><i class="bi bi-play-circle"></i> Why it matters for everyone</span><span>09:15</span></li>
                                        <li><span><i class="bi bi-play-circle"></i> Types of cyber threats</span><span>14:30</span></li>
                                        <li><span><i class="bi bi-file-earmark-pdf"></i> Course handbook (PDF)</span><span>Notes</span></li>
                                        <li><span><i class="bi bi-play-circle"></i> The CIA triad explained</span><span>11:05</span></li>
                                        <li><span><i class="bi bi-play-circle"></i> Week 1 recap</span><span>06:20</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Module 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mod2">
                                    Week 2: Threats & Attacks
                                    <span class="cd-mod-count">6 lessons</span>
                                </button>
                            </h2>
                            <div id="mod2" class="accordion-collapse collapse" data-bs-parent="#courseContent">
                                <div class="accordion-body p-0">
                                    <ul class="cd-lessons">
                                        <li><span><i class="bi bi-play-circle"></i> Phishing attacks in depth</span><span>15:10</span></li>
                                        <li><span><i class="bi bi-play-circle"></i> Malware & ransomware</span><span>13:45</span></li>
                                        <li><span><i class="bi bi-file-earmark-pdf"></i> Threat examples (PDF)</span><span>Notes</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Module 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mod3">
                                    Week 3: Protection in Practice
                                    <span class="cd-mod-count">7 lessons</span>
                                </button>
                            </h2>
                            <div id="mod3" class="accordion-collapse collapse" data-bs-parent="#courseContent">
                                <div class="accordion-body p-0">
                                    <ul class="cd-lessons">
                                        <li><span><i class="bi bi-play-circle"></i> Strong passwords & 2FA</span><span>10:30</span></li>
                                        <li><span><i class="bi bi-play-circle"></i> Securing your devices</span><span>12:00</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Module 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mod4">
                                    Week 4: Assessment & Certification
                                    <span class="cd-mod-count">5 lessons</span>
                                </button>
                            </h2>
                            <div id="mod4" class="accordion-collapse collapse" data-bs-parent="#courseContent">
                                <div class="accordion-body p-0">
                                    <ul class="cd-lessons">
                                        <li><span><i class="bi bi-play-circle"></i> Full course revision</span><span>18:00</span></li>
                                        <li><span><i class="bi bi-ui-checks"></i> Final assessment</span><span>Quiz</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT: enroll card -->
            <div class="col-lg-4">
                <div class="cd-sidebar">

                    <div class="cd-enroll-card">
                        <div class="cd-enroll-thumb">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div class="cd-enroll-body">
                            <div class="cd-price">FREE</div>
                            <div class="cd-price-sub">for Haryana polytechnic students</div>

                            <a href="/register" class="btn btn-navy w-100 mt-3">Enroll Now</a>

                            <ul class="cd-facts">
                                <li><i class="bi bi-clock"></i> 8 weeks · self paced</li>
                                <li><i class="bi bi-phone"></i> Learn on any device</li>
                                <li><i class="bi bi-award"></i> Government certificate</li>
                                <li><i class="bi bi-arrow-repeat"></i> Lifetime access</li>
                            </ul>
                        </div>
                    </div>

                    <div class="cd-trainer">
                        <div class="cd-trainer-avatar">AK</div>
                        <div>
                            <h6>Dr. A. Kumar</h6>
                            <p>HOD, Computer Science</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Mobile fixed enroll bar -->
<div class="cd-mobile-bar d-lg-none">
    <div>
        <div class="cd-price">FREE</div>
        <div class="cd-price-sub">Certificate included</div>
    </div>
    <a href="/register" class="btn btn-navy">Enroll Now</a>
</div>

@endsection