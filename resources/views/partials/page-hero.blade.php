{{-- Reusable navy hero for information pages. Usage:
     @include('partials.page-hero', ['title' => '...', 'subtitle' => '...']) --}}
<section style="background:#0d2a5c;color:#fff;padding:56px 0;">
    <div class="container text-center">
        <h1 style="font-weight:700;">{{ $title }}</h1>
        @isset($subtitle)
            <p style="color:#cdd6e8;margin-bottom:0;">{{ $subtitle }}</p>
        @endisset
    </div>
</section>
