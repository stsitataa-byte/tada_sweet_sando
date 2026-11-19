@extends('layouts.app')

@section('title', 'About | TADA SWEETSANDO')

@section('content')
<section class="about">
    <div class="about__copy">
        <h1 class="display-title display-title--hero">{{ config('sando.about.title') }}</h1>
        @foreach(config('sando.about.text', []) as $para)
            <p>{{ $para }}</p>
        @endforeach
        <a class="btn btn--pink" href="{{ route('shop') }}">Lihat varian</a>
    </div>
    <div class="about__art">
        @include('partials.img', ['src' => config('sando.images.about'), 'alt' => 'Tentang TADA SWEETSANDO', 'class' => 'about__img'])
    </div>
</section>

@include('partials.mission')
@include('partials.faq')
@endsection