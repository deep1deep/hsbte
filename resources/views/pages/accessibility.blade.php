@extends('layouts.app')

@section('title', 'Accessibility Statement - ' . config('portal.name'))

@section('content')

@include('partials.page-hero', [
    'title'    => 'Accessibility Statement',
    'subtitle' => 'Our commitment to making this portal usable by everyone',
])

<section class="section-pad">
    <div class="container" style="max-width:820px;">

        <h2 class="h4 mb-3">Our commitment</h2>
        <p class="text-muted">
            The {{ config('portal.org') }} is committed to making this portal usable by
            as many people as possible, including people with visual, hearing, motor or
            cognitive disabilities. We aim to meet the Web Content Accessibility
            Guidelines (WCAG) 2.1 at Level AA, as required by the Guidelines for Indian
            Government Websites and Apps (GIGW).
        </p>

        <h2 class="h4 mt-5 mb-3">Current status</h2>
        <div class="admin-card my-3" style="border-left:4px solid #f0a500;">
            <div class="admin-card-body">
                <p class="text-muted small mb-0">
                    <strong>Partially conformant.</strong> This portal meets many but not
                    yet all Level AA criteria. A formal accessibility audit has not been
                    completed. We are publishing this statement openly rather than
                    claiming a level of conformance we have not verified.
                </p>
            </div>
        </div>

        <h2 class="h4 mt-5 mb-3">What already works</h2>
        <ul class="text-muted">
            <li>Pages can be navigated using a keyboard alone</li>
            <li>The layout adjusts to mobile phones, tablets and desktop screens</li>
            <li>Text can be enlarged using your browser's zoom without losing content</li>
            <li>Form fields have labels that screen readers can announce</li>
            <li>Page headings follow a logical structure</li>
        </ul>

        <h2 class="h4 mt-5 mb-3">Known limitations</h2>
        <p class="text-muted">We are aware of the following, and are working on them:</p>
        <ul class="text-muted">
            <li><strong>Video captions.</strong> Lesson videos do not yet carry captions
                or text transcripts. This affects learners who are deaf or hard of hearing.</li>
            <li><strong>Language.</strong> Content is currently available in English only.
                Hindi is planned.</li>
            <li><strong>Uploaded documents.</strong> PDF study material is supplied by
                trainers and may not always be tagged for screen readers.</li>
            <li><strong>Colour contrast.</strong> Some elements have not yet been formally
                checked against the Level AA contrast ratio.</li>
        </ul>

        <h2 class="h4 mt-5 mb-3">Getting help</h2>
        <p class="text-muted">
            If you cannot access any part of this portal, or need material in a different
            format, please contact us at
            <a href="mailto:{{ config('portal.contact.email') }}">{{ config('portal.contact.email') }}</a>
            or call {{ config('portal.contact.phone') }}. Tell us the page address and
            what you were trying to do, and we will help you and work to fix the problem.
        </p>

        <h2 class="h4 mt-5 mb-3">Feedback</h2>
        <p class="text-muted">
            We welcome reports of accessibility problems. Your feedback directly shapes
            what we fix next.
        </p>

        <p class="text-muted small mt-5 mb-0">
            Content last reviewed: {{ config('portal.last_reviewed') }}
        </p>

    </div>
</section>

@endsection
