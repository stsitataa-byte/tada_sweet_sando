@extends('layouts.app')

@section('title', 'Shop | TADA SWEETSANDO')

@section('content')
    @if(session('notice'))
        <div class="notice">{{ session('notice') }}</div>
    @endif

    @foreach($storeProducts as $storeProduct)
        <h2 class="sr-only">{{ $storeProduct->name }}</h2>
    @endforeach

    @include('partials.collection', ['catalog' => $catalog])
@endsection