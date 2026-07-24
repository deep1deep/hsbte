{{-- "How it works" — 4-step process. It reveals on scroll (the JS only adds a
     single class), everything else is CSS animation. --}}
@php
    $steps = [
        ['icon' => 'bi-person-plus',   'title' => 'Register',      'text' => 'Create your free student account with your enrolment and department details.'],
        ['icon' => 'bi-journal-check', 'title' => 'Choose a Course','text' => 'Browse published programmes by department and enrol in one click.'],
        ['icon' => 'bi-play-btn',      'title' => 'Learn',         'text' => 'Work through video lessons and study material at your own pace.'],
        ['icon' => 'bi-patch-check',   'title' => 'Get Certified', 'text' => 'Finish every lesson to earn a certificate employers can verify online.'],
    ];
@endphp

{{-- If JS is disabled the steps stay stuck at opacity:0 — the content disappears.
     This fallback makes them visible directly (without the animation), so the
     content can always be read. --}}
<noscript>
    <style>
        .process-step,
        .process-cta{opacity:1 !important;transform:none !important;}
        .process-line{transform:scaleX(1) !important;}
    </style>
</noscript>

<section class="section-pad process-section" aria-labelledby="process-heading">
    <div class="container">

        <div class="process-head">
            <h3 id="process-heading">How It Works</h3>
            <p>From registration to a verified certificate — in four simple steps</p>
        </div>

        <ol class="process-track">
            {{-- gold connector line behind the icons; it fills from left to right on scroll --}}
            <span class="process-line" aria-hidden="true"></span>

            @foreach($steps as $index => $step)
                <li class="process-step" style="--i:{{ $index }}">
                    <div class="process-icon">
                        <i class="bi {{ $step['icon'] }}" aria-hidden="true"></i>
                        <span class="process-num">{{ $index + 1 }}</span>
                    </div>
                    <h4 class="process-title">{{ $step['title'] }}</h4>
                    <p class="process-text">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>

        <div class="process-cta">
            @guest
                <a href="{{ route('register') }}" class="process-btn">Get Started — it's free</a>
            @else
                <a href="{{ route('courses') }}" class="process-btn">Browse Courses</a>
            @endguest
        </div>

    </div>
</section>

<script>
/* As soon as the section enters the screen .is-visible is added — the animation
   lives in the CSS. If IntersectionObserver is unavailable the section becomes
   visible immediately (no JS = no hidden content, which is essential). */
(function () {
    var section = document.querySelector('.process-section');
    if (!section) return;

    if (!('IntersectionObserver' in window)) {
        section.classList.add('is-visible');
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);   // run only once
            }
        });
    }, { threshold: 0.2 });

    observer.observe(section);
})();
</script>
