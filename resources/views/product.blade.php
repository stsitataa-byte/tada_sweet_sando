@extends('layouts.app')
@section('title', $products[$current]['name'].' — tada sweetsando')
@section('body_class', 'pdp-page')
@section('content')
@php $d = $products[$current]['detail'] ?? null; @endphp

@if ($d)
<section class="sd">

  <div class="sd-c1">
    <div class="sd-c1-top">
      <h2 class="sd-deco">{!! nl2br(e($d['title'])) !!}</h2>
    </div>
    <div class="sd-c1-bot">
      <h1 class="sd-fruit">{{ $d['name'] }}</h1>
      <div class="sd-stars" role="img" aria-label="Rating {{ $d['rating'] }} dari 5">{{ str_repeat('★', $d['rating']) }}{{ str_repeat('☆', 5 - $d['rating']) }}</div>
      <p class="sd-desc">{{ $d['desc'] }}</p>
      <div class="sd-buy">
        <strong>Rp {{ number_format($products[$current]['price'], 0, ',', '.') }}</strong>
        <button class="btn sd-add" id="detailAdd" data-s="{{ $current }}" @disabled($products[$current]['stock'] < 1)>
          {{ $products[$current]['stock'] > 0 ? 'TAMBAHKAN KE KERANJANG' : 'STOK HABIS' }}
        </button>
      </div>
    </div>
  </div>

  <div class="sd-c2">
    <figure class="sd-photo">
      <img src="{{ asset($d['photo']) }}" alt="{{ $products[$current]['name'] }}" onerror="this.parentNode.classList.add('no-img')">
    </figure>
  </div>

  <div class="sd-c3">
    <div class="sd-c3-title"><h2>ingredients</h2></div>
    <div class="sd-c3-body">
      <table class="sd-gizi">
        <thead><tr><th>Kandungan gizi</th><th>Jumlah</th></tr></thead>
        <tbody>
          @foreach ($d['nutrition'] as [$k, $v])
          <tr><td>{{ $k }}</td><td>{{ $v }}</td></tr>
          @endforeach
        </tbody>
      </table>
      <ul class="sd-bahan">
        @foreach ($d['bahan'] as $b)
        <li>{!! nl2br(e($b)) !!}</li>
        @endforeach
      </ul>
    </div>
    <p class="sd-note">{!! nl2br(e($d['note'])) !!}</p>
  </div>

</section>
@else
<section class="pdp" id="pdp">
  <div class="col col-l" id="colL"></div>
  <div class="col col-c" id="colC"></div>
  <div class="col col-r" id="colR"></div>
</section>
@endif

@include('partials.faq')
@include('partials.social')
@endsection