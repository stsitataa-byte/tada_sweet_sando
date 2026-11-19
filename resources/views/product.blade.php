@extends('layouts.app')
<<<<<<< HEAD

@section('title', 'Menu | TADA SWEETSANDO')
@section('body_class', 'page-menu')

@section('content')
{{--
    MENU TIGA KOLOM (desain video kedua).
    Semua isi (nama, deskripsi, foto, warna, ingredients, gizi, harga) digambar oleh
    public/js/menu.js dari config/sando.php. Satu halaman untuk semua varian.
--}}
<div class="menu" id="menu"
     data-slug="{{ $current['slug'] }}"
     data-url="{{ route('product', ['slug' => '__slug__']) }}">

    <nav class="variant-tabs" aria-label="Pilih varian">
        @foreach($catalog as $p)
            <button type="button" class="variant-tab {{ $p['slug'] === $current['slug'] ? 'is-active' : '' }}" data-variant="{{ $p['slug'] }}">{{ $p['name'] }}</button>
        @endforeach
    </nav>

    <section class="menu__grid">

        {{-- KOLOM KIRI: informasi produk --}}
        <div class="col col--info">
            <div class="info__top">
                <h2>FRUIT SWEET<br>SANDO</h2>
            </div>
            <div class="info__body">
                <h1 class="info__name" id="pName">{{ $current['title'] }}</h1>
                <div class="info__stars" id="pStars" aria-label="Rating"></div>
                <p class="info__desc" id="pDesc"></p>

                <div class="buy">
                    <div class="buy__sizes" role="group" aria-label="Pilih ukuran">
                        <button type="button" class="size-btn is-active" data-size="small">Small</button>
                        <button type="button" class="size-btn" data-size="regular">Regular</button>
                    </div>
                    <div class="buy__row">
                        <div class="qty" aria-label="Jumlah">
                            <button type="button" data-qty="-1" aria-label="Kurangi"><svg width="14" height="14"><use href="#i-minus"/></svg></button>
                            <span id="pQty">1</span>
                            <button type="button" data-qty="1" aria-label="Tambah"><svg width="14" height="14"><use href="#i-plus"/></svg></button>
                        </div>
                        <b class="buy__price" id="pPrice"></b>
                    </div>
                    <button type="button" class="btn btn--pink btn--block" id="pAdd">ADD TO CART</button>
                    <p class="buy__msg" id="pMsg" role="status"></p>
                </div>
            </div>
        </div>

        {{-- KOLOM TENGAH: foto produk (foto sama untuk Small dan Regular) --}}
        <div class="col col--photo">
            <img id="pImg" src="" alt="" hidden>
            <div class="img-placeholder" id="pPlaceholder" hidden>
                <span>Taruh foto di<br><code id="pPath"></code></span>
            </div>
        </div>

        {{-- KOLOM KANAN: ingredients --}}
        <div class="col col--ingr">
            <div class="ingr__top"><h2>ingredients</h2></div>
            <div class="ingr__tables">
                <div class="ingr__nutri">
                    <table>
                        <thead><tr><th>Kandungan gizi</th><th>Jumlah</th></tr></thead>
                        <tbody id="pNutri"></tbody>
                    </table>
                </div>
                <div class="ingr__list" id="pIngr"></div>
            </div>
            <div class="ingr__note" id="pNote"></div>
        </div>

    </section>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/menu.js') }}?v={{ @filemtime(public_path('js/menu.js')) }}"></script>
@endpush
=======
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
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
