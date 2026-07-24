{{-- Read-only star rating for course cards.
     Usage: @include('partials.stars', ['avg' => $course->averageRating(), 'count' => $course->reviewsCount()]) --}}
@php($avg = $avg ?? 0)
@php($count = $count ?? 0)
@if($count > 0)
    <span class="stars-static" aria-label="Rated {{ number_format($avg, 1) }} out of 5 from {{ $count }} reviews">
        @for($i = 1; $i <= 5; $i++)
            <i class="bi {{ $i <= round($avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
        @endfor
    </span>
    <span class="rating-meta">{{ number_format($avg, 1) }} ({{ $count }})</span>
@else
    <span class="rating-meta"><i class="bi bi-star"></i> No reviews yet</span>
@endif
