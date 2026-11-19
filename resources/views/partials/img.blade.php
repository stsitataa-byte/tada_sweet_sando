{{--
    Tempat menempelkan gambar (dipakai di banyak halaman).
    Cara pakai:  @include('partials.img', ['src' => 'assets/story.png', 'alt' => 'Cerita', 'class' => 'story__img'])
    - Jika file ada di folder public/ -> gambar tampil.
    - Jika belum ada -> kotak placeholder yang menyebutkan lokasi file (layout tidak rusak).
--}}
@php($hasImage = ! empty($src) && file_exists(public_path($src)))
@if($hasImage)
    <img src="{{ asset($src) }}" alt="{{ $alt ?? '' }}" class="{{ $class ?? '' }}" loading="lazy">
@else
    <div class="img-placeholder {{ $class ?? '' }}" role="img" aria-label="{{ $alt ?? 'Gambar belum tersedia' }}">
        <span><br><code></code></span>
    </div>
@endif