<footer class="mt-5" style="background:#0d2a5c; color:white;">

    <div class="container py-5">

        <div class="row">

            <!-- About -->
            <div class="col-lg-4 mb-4">

                <h4 class="fw-bold">
                    HSBTE Training Portal
                </h4>

                <p>
                    A Government Training & Learning Management System
                    for students, trainers and administrators.
                </p>

            </div>

            <!-- Quick Links -->
            <div class="col-lg-4 mb-4">

                <h5>Quick Links</h5>

                <ul class="list-unstyled">

                    <li><a href="/" class="text-white text-decoration-none">Home</a></li>

                    <li><a href="{{ route('courses') }}" class="text-white text-decoration-none">Courses</a></li>

                    <li><a href="{{ route('notices') }}" class="text-white text-decoration-none">Notices</a></li>

                    <li><a href="{{ route('certificate.verify') }}" class="text-white text-decoration-none">Verify Certificate</a></li>

                    <li><a href="{{ route('about') }}" class="text-white text-decoration-none">About</a></li>

                    <li><a href="{{ route('contact') }}" class="text-white text-decoration-none">Contact Us</a></li>

                </ul>

            </div>

            <!-- Contact -->
            <div class="col-lg-4 mb-4">

                <h5>Contact</h5>

                <p class="mb-1">
                    📍 {{ config('portal.org') }}
                </p>

                <p class="mb-1">
                    📧 <a href="mailto:{{ config('portal.contact.email') }}"
                          class="text-white text-decoration-none">{{ config('portal.contact.email') }}</a>
                </p>

                <p>
                    ☎ {{ config('portal.contact.phone') }}
                </p>

            </div>

        </div>

        <hr style="border-color: rgba(255,255,255,.25);">

        <div class="text-center">

            <ul class="list-inline mb-2 small">
                <li class="list-inline-item">
                    <a href="{{ route('privacy') }}" class="text-white text-decoration-none">Privacy Policy</a>
                </li>
                <li class="list-inline-item">·</li>
                <li class="list-inline-item">
                    <a href="{{ route('terms') }}" class="text-white text-decoration-none">Terms of Use</a>
                </li>
                <li class="list-inline-item">·</li>
                <li class="list-inline-item">
                    <a href="{{ route('accessibility') }}" class="text-white text-decoration-none">Accessibility</a>
                </li>
            </ul>

            © {{ date('Y') }} {{ config('portal.name') }}.
            All Rights Reserved.

            <div class="small mt-2" style="color:rgba(255,255,255,.65);">
                Content last reviewed: {{ config('portal.last_reviewed') }}
            </div>

        </div>

    </div>

</footer>