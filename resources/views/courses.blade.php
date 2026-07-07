@extends('layouts.app')

@section('title', 'Explore Courses - HSBTE Training Portal')

@section('content')

<!-- ================= COURSES HERO ================= -->
<section class="courses-hero">
    <div class="container text-center">
        <h1>Explore Courses</h1>
        <p>120+ free certified programmes for Haryana students</p>

        <div class="course-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search by course name...">
            <button type="button" class="btn course-search-btn">Search</button>
        </div>
    </div>
</section>

<!-- ================= DEPARTMENT CHIPS ================= -->
<section class="chips-bar">
    <div class="container">
        <div class="chips-scroll">
            <a href="#" class="chip chip-active">All</a>
            <a href="#" class="chip">Computer Science</a>
            <a href="#" class="chip">Electronics</a>
            <a href="#" class="chip">Mechanical</a>
            <a href="#" class="chip">Civil</a>
            <a href="#" class="chip">Electrical</a>
        </div>
    </div>
</section>

<!-- ================= COURSE GRID ================= -->
<section class="section-pad">
    <div class="container">

        <div class="results-bar">
            <span>Showing 6 of 120 courses</span>
            <span>Sort: Most Enrolled <i class="bi bi-chevron-down"></i></span>
        </div>

        <div class="row g-4">

            <!-- Course 1 -->
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
                            <a href="#" class="btn btn-sm btn-navy">View Course</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course 2 -->
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
                            <a href="#" class="btn btn-sm btn-navy">View Course</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course 3 -->
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
                            <a href="#" class="btn btn-sm btn-navy">View Course</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="course-card">
                    <div class="course-thumb course-thumb-coral">
                        <i class="bi bi-database"></i>
                    </div>
                    <div class="course-body">
                        <span class="course-dept">Computer Science</span>
                        <h5>Data Fundamentals</h5>
                        <div class="course-meta">
                            <span><i class="bi bi-clock"></i> 6 weeks</span>
                            <span><i class="bi bi-people"></i> 980 enrolled</span>
                        </div>
                        <div class="course-foot">
                            <span class="course-free">FREE · Certificate</span>
                            <a href="#" class="btn btn-sm btn-navy">View Course</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="course-card">
                    <div class="course-thumb course-thumb-pink">
                        <i class="bi bi-globe"></i>
                    </div>
                    <div class="course-body">
                        <span class="course-dept">Digital Governance</span>
                        <h5>Digital Governance Workshop</h5>
                        <div class="course-meta">
                            <span><i class="bi bi-clock"></i> 2 weeks</span>
                            <span><i class="bi bi-people"></i> 640 enrolled</span>
                        </div>
                        <div class="course-foot">
                            <span class="course-free">FREE · Certificate</span>
                            <a href="#" class="btn btn-sm btn-navy">View Course</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="course-card">
                    <div class="course-thumb course-thumb-gray">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="course-body">
                        <span class="course-dept">Mechanical</span>
                        <h5>AutoCAD Basics</h5>
                        <div class="course-meta">
                            <span><i class="bi bi-clock"></i> 4 weeks</span>
                            <span><i class="bi bi-people"></i> 720 enrolled</span>
                        </div>
                        <div class="course-foot">
                            <span class="course-free">FREE · Certificate</span>
                            <a href="#" class="btn btn-sm btn-navy">View Course</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-navy">Load More Courses</a>
        </div>

    </div>
</section>

@endsection