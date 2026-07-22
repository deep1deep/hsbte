@extends('layouts.app')

@section('title', 'Help & FAQ - ' . config('portal.name'))

@section('content')

@include('partials.page-hero', [
    'title'    => 'Help & Frequently Asked Questions',
    'subtitle' => 'Answers to the most common questions',
])

<section class="section-pad">
    <div class="container" style="max-width:820px;">

        @php
            $faqs = [
                'Accounts and login' => [
                    ['Who can register on this portal?',
                     'Students of technical and polytechnic institutions in Haryana can register free of charge. Trainer accounts are created by the portal administrator — trainers cannot register themselves.'],
                    ['I forgot my password. What do I do?',
                     'Use the "Forgot your password?" link on the login page. Enter your registered email address and you will be sent a link to set a new password. The link is valid for 60 minutes.'],
                    ['I did not receive the password reset email.',
                     'Check your spam or junk folder first. If it still has not arrived after a few minutes, contact us using the details on the contact page and we will help you.'],
                    ['My account says it is inactive.',
                     'An administrator has deactivated the account. Please contact us to find out why and to have it restored.'],
                    ['Can I register twice with different email addresses?',
                     'No. Each person may hold only one student account. Registration checks your Aadhaar and mobile number to prevent duplicates.'],
                ],
                'Courses and lessons' => [
                    ['How do I enrol in a course?',
                     'Open the Courses page, choose a course and click "View Course", then click Enrol. You must be logged in as a student.'],
                    ['Do I have to pay anything?',
                     'No. Every course on this portal is completely free.'],
                    ['A video will not play. What should I check?',
                     'Check your internet connection first, then try refreshing the page or using a different browser. If the problem continues, contact us and tell us the course and lesson name.'],
                    ['Can I learn at my own pace?',
                     'Yes. There is no fixed timetable. Your progress is saved automatically, so you can stop and continue later.'],
                    ['Can I download the videos?',
                     'No. Videos may be watched on the portal only. PDF study material can be opened and saved.'],
                ],
                'Certificates' => [
                    ['How do I get my certificate?',
                     'Complete every lesson in the course. Your certificate is then created automatically and appears on your dashboard.'],
                    ['My certificate says "pending". Why?',
                     'Some courses are set so that the trainer reviews and issues each certificate personally. Your certificate number is already reserved — you will be able to download it once the trainer has issued it.'],
                    ['How can an employer check my certificate is genuine?',
                     'Anyone can visit the certificate verification page and enter the certificate number. No login is needed. It will show the student name, course and issue date.'],
                    ['I lost my certificate file.',
                     'You can download it again at any time from your dashboard. Certificates are not deleted.'],
                ],
            ];
        @endphp

        @foreach($faqs as $section => $items)
            <h2 class="h4 mt-4 mb-3">{{ $section }}</h2>

            <div class="accordion mb-4" id="faq-{{ $loop->index }}">
                @foreach($items as $i => [$question, $answer])
                    @php $id = 'q-' . $loop->parent->index . '-' . $i; @endphp
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#{{ $id }}"
                                    aria-expanded="false" aria-controls="{{ $id }}">
                                {{ $question }}
                            </button>
                        </h3>
                        <div id="{{ $id }}" class="accordion-collapse collapse"
                             data-bs-parent="#faq-{{ $loop->parent->index }}">
                            <div class="accordion-body text-muted">
                                {{ $answer }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="admin-card mt-5">
            <div class="admin-card-body text-center">
                <h2 class="h6 mb-2">Still need help?</h2>
                <p class="text-muted small mb-3">
                    If your question is not answered here, get in touch and we will help you.
                </p>
                <a href="{{ route('contact') }}" class="btn btn-navy btn-sm">Contact us</a>
            </div>
        </div>

        <p class="text-muted small mt-5 mb-0">
            Content last reviewed: {{ config('portal.last_reviewed') }}
        </p>

    </div>
</section>

@endsection
