@extends('layouts.app')

@section('title', 'Shop | TADA SWEETSANDO')

@section('content')
    @if(session('notice'))
        <div class="notice">{{ session('notice') }}</div>
    @endif

    @include('partials.collection', ['catalog' => $catalog])
@endsection