@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<section class="section-pad">
    <div class="container">
        <h2>Welcome, {{ auth()->user()->name }} 👋</h2>
        <p class="text-muted">You are logged in as a <strong>Student</strong>.</p>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-outline-navy">Logout</button>
        </form>
    </div>
</section>
@endsection