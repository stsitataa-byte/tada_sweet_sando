{{-- SOCIAL MEDIA. URL diisi di config/sando.php (bagian 'socials'). --}}
<section class="social" aria-label="Social media">
    @foreach(config('sando.socials', []) as $label => $url)
        @if(! empty($url))
            <a href="{{ $url }}" target="_blank" rel="noopener">{{ $label }}</a>
        @else
            <span title="Link belum diisi di config/sando.php">{{ $label }}</span>
        @endif
    @endforeach
</section>