<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'HSBTE Training Portal')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

       

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
        <link href="bootstrap.css">

{{-- ?v=<file modified time> — cache busting.
     nginx caches the CSS for 30 days (which is good for performance), but
     without a version the browser holds on to the old CSS and never shows the
     new design. When the file changes this number updates automatically, so the
     browser fetches the fresh copy immediately. --}}
@php
    $stylePath    = public_path('css/style.css');
    $styleVersion = file_exists($stylePath) ? filemtime($stylePath) : null;
@endphp
<link rel="stylesheet"
      href="{{ asset('css/style.css') }}{{ $styleVersion ? '?v=' . $styleVersion : '' }}">

<link href="bootstrap-icons.css">

<link href="google-font.css">

    <style>

        body{
            font-family:'Poppins',sans-serif;
            background:#f5f7fa;
        }
    

    </style>

    @stack('styles')

</head>

<body>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>