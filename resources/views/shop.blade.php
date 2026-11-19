@extends('layouts.app')
<<<<<<< HEAD

@section('title', 'Shop | TADA SWEETSANDO')

@section('content')
    @if(session('notice'))
        <div class="notice">{{ session('notice') }}</div>
    @endif

    @include('partials.collection', ['catalog' => $catalog])
=======
@section('title', 'Shop — tada sweetsando')
@section('content')
<section class="page-t"><h1 class="mega-s">SHOP ALL SANDO</h1><p class="mono">Pilih rasa favoritmu, geser untuk melihat semuanya →</p></section>
@include('partials.collection')
@include('partials.faq')
@include('partials.social')
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
@endsection