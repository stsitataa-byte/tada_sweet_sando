@extends('layouts.app')
@section('title', 'Shop — tada sweetsando')
@section('content')
<section class="page-t"><h1 class="mega-s">SHOP ALL SANDO</h1><p class="mono">Pilih rasa favoritmu, geser untuk melihat semuanya →</p></section>
@include('partials.collection')
@include('partials.faq')
@include('partials.social')
@endsection