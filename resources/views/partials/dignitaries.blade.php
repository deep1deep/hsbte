{{-- Leadership / dignitaries strip — data comes from config/portal.php.
     If a photo is missing, an initials-based avatar is shown; the layout never breaks. --}}
@php $dignitaries = config('portal.dignitaries', []); @endphp

{{-- Setting 'show_dignitaries' => true in config/portal.php (or setting
     PORTAL_SHOW_DIGNITARIES=true on Render) will bring this section back. --}}
@if(config('portal.show_dignitaries') && count($dignitaries))
<section class="section-pad dignitary-section" aria-labelledby="dignitaries-heading">
    <div class="container">

        <div class="dignitary-head">
            <h3 id="dignitaries-heading">Under the Guidance of</h3>
            <span class="dignitary-rule" aria-hidden="true"></span>
        </div>

        <ul class="dignitary-grid">
            @foreach($dignitaries as $person)
                @php
                    $file      = $person['photo'] ?? null;
                    $photoPath = $file ? public_path('images/dignitaries/' . $file) : null;
                    $hasPhoto  = $photoPath && file_exists($photoPath);
                    // so the browser doesn't show the old one when the photo is replaced
                    $photoUrl  = $hasPhoto
                        ? asset('images/dignitaries/' . $file) . '?v=' . filemtime($photoPath)
                        : null;

                    // "Sh. Nayab Singh Saini" -> "NS"  (with the honorific/suffix removed)
                    $words = preg_split('/\s+/', preg_replace('/,.*$/', '', $person['name']));
                    $words = array_values(array_filter($words, fn ($w) => ! in_array(rtrim($w, '.'), ['Sh', 'Smt', 'Dr', 'Shri', 'Ms', 'Mr'], true)));
                    $initials = strtoupper(mb_substr($words[0] ?? '', 0, 1) . mb_substr($words[count($words) - 1] ?? '', 0, 1));
                @endphp

                <li class="dignitary-item">
                    <div class="dignitary-photo">
                        @if($hasPhoto)
                            <img src="{{ $photoUrl }}"
                                 alt="{{ $person['name'] }}, {{ $person['designation'] }}"
                                 loading="lazy" width="150" height="150">
                        @else
                            <span class="dignitary-initials" role="img"
                                  aria-label="{{ $person['name'] }}">{{ $initials }}</span>
                        @endif
                    </div>

                    <p class="dignitary-role">{{ $person['designation'] }}</p>
                    <p class="dignitary-name">{{ $person['name'] }}</p>
                </li>
            @endforeach
        </ul>

    </div>
</section>
@endif
