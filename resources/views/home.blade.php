@extends('layouts.app')

@section('title', 'TADA SWEETSANDO | Fresh Fruit Sando')

@section('content')
@php
    $heroSrc = config('sando.images.hero') ?: ($catalog['mix-fruit']['image'] ?? null);
    $hero = config('sando.hero');
@endphp

{{-- ============ HERO ============ --}}
<section class="hero">
    <div class="hero__text">
        <p class="hero__kicker">{{ $hero['kicker'] }}</p>
        <h1 class="display-title display-title--hero">{{ $hero['headline'] }}</h1>
        <p class="hero__lead">{{ $hero['text'] }}</p>
        <a class="btn btn--pink btn--big" href="{{ route('shop') }}">{{ $hero['button'] }} <svg width="18" height="18"><use href="#i-arrow"/></svg></a>
    </div>

    <div class="hero__visual">
        <svg class="deco deco--1" width="70" height="70"><use href="#i-strawberry"/></svg>
        <svg class="deco deco--2" width="64" height="64"><use href="#i-grape"/></svg>
        <svg class="deco deco--3" width="64" height="64"><use href="#i-mango"/></svg>
        <svg class="deco deco--4" width="48" height="48"><use href="#i-sparkle"/></svg>
        <div class="hero__photo">
            @include('partials.img', ['src' => $heroSrc, 'alt' => 'Fruit sando TADA SWEETSANDO', 'class' => 'hero__img'])
        </div>
    </div>
</section>

{{-- ============ STORY (tab) ============ --}}
<section class="story" id="story">
    <div class="tabs" role="tablist">
        @foreach(config('sando.story.tabs') as $i => $tab)
            <button type="button" class="tab {{ $i === 0 ? 'is-active' : '' }}" role="tab" data-tab="{{ $i }}">{{ $tab['label'] }}</button>
        @endforeach
    </div>

    @foreach(config('sando.story.tabs') as $i => $tab)
        <div class="story__panel {{ $i === 0 ? 'is-active' : '' }}" data-panel="{{ $i }}">
            <div class="story__copy">
                @foreach($tab['lines'] as $n => $line)
                    <h2 class="display-title display-title--story-{{ $n % 3 }}">{{ $line }}</h2>
                @endforeach
                <p>{{ $tab['text'] }}</p>
            </div>
            <div class="story__art">
                @include('partials.img', ['src' => config('sando.images.story'), 'alt' => 'Cerita TADA SWEETSANDO', 'class' => 'story__img'])
            </div>
        </div>
    @endforeach
</section>

{{-- ============ KATALOG ============ --}}
@include('partials.collection', ['catalog' => $catalog])

{{-- ============ SHOWCASE ============ --}}
@php($first = reset($catalog))
<section class="showcase" id="showcase" data-slug="{{ $first['slug'] ?? '' }}">
    <div class="showcase__info">
        <p class="showcase__label">Pick your sando</p>
        <h2 class="showcase__name" data-show-name>{{ $first['name'] ?? '' }}</h2>
        <p class="showcase__price" data-show-price>{{ ($first['price_from'] ?? null) ? 'Mulai ' . $first['price_from'] : 'Harga belum diisi' }}</p>
        <p class="showcase__size">Tersedia: Small &amp; Regular</p>
        <a class="btn btn--pink" data-show-link href="{{ route('product', ['slug' => $first['slug'] ?? '']) }}">Lihat detail</a>
    </div>
    <div class="showcase__photo" data-show-photo>
        @include('partials.img', ['src' => $first['image'] ?? null, 'alt' => $first['name'] ?? '', 'class' => 'showcase__img'])
    </div>
    <div class="showcase__pick">
        @foreach($catalog as $p)
            <button type="button" class="swatch {{ $loop->first ? 'is-active' : '' }}" data-show-slug="{{ $p['slug'] }}" style="--card: {{ $p['color'] }}">{{ $p['name'] }}</button>
        @endforeach
    </div>
</section>

{{-- ============ TESTIMONI ============ --}}
<section class="reviews" id="reviews">
    <h2 class="display-title display-title--pinkbig">THEY LIKE US</h2>
    <div class="reviews__grid">
        @php($reviews = config('sando.testimonials', []))
        @forelse($reviews as $r)
            <figure class="review">
                @if(! empty($r['photo']))
                    @include('partials.img', ['src' => $r['photo'], 'alt' => $r['name'] ?? '', 'class' => 'review__img'])
                @endif
                <blockquote>{{ $r['text'] }}</blockquote>
                <figcaption>{{ $r['name'] ?? '' }}</figcaption>
            </figure>
        @empty
            @for($i = 1; $i <= 3; $i++)
                <figure class="review review--empty">
                    @include('partials.img', ['src' => 'assets/review-' . $i . '.png', 'alt' => 'Foto ulasan ' . $i, 'class' => 'review__img'])
                    <blockquote>Ulasan pelanggan akan tampil di sini.</blockquote>
                    <figcaption>Isi di config/sando.php (testimonials)</figcaption>
                </figure>
            @endfor
        @endforelse
    </div>
</section>

{{-- ============ MISI ============ --}}
@include('partials.mission')

{{-- ============ FAQ ============ --}}
@include('partials.faq')
@endsection