@extends('layouts.app')

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