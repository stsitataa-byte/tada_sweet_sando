<<<<<<< HEAD
{{-- SOCIAL MEDIA. URL diisi di config/sando.php (bagian 'socials'). --}}
<section class="social" aria-label="Social media">
    @foreach(config('sando.socials', []) as $label => $url)
        @if(! empty($url))
            <a href="{{ $url }}" target="_blank" rel="noopener">{{ $label }}</a>
        @else
            <span title="Link belum diisi di config/sando.php">{{ $label }}</span>
        @endif
    @endforeach
=======
<section class="soc">
  @php $s = config('sando.social'); @endphp
  <a href="{{ $s['tiktok'] }}" class="s1">TIKTOK</a>
  <a href="{{ $s['instagram'] }}" class="s2">INSTAGRAM</a>
  <a href="{{ $s['pinterest'] }}" class="s3">PINTEREST</a>
  <a href="{{ $s['whatsapp'] }}" class="s4">WHATSAPP</a>
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
</section>