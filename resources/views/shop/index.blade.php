@extends('layouts.store')

@section('title', 'Menu')

@section('content')
    <section class="hero">
        <div>
            <div class="eyebrow">Jeda manis di harimu</div>
            <h1>Roti lembut,<br>isi yang bikin rindu.</h1>
            <p>Sandwich manis dibuat segar setiap hari, dengan isian favorit yang pas untuk menemani harimu.</p>
            <a class="btn" href="#menu">Lihat menu <span aria-hidden="true">↓</span></a>
        </div>
        <div class="hero-art" aria-hidden="true">🍓🥪</div>
    </section>

    <div class="section-head" id="menu">
        <div><div class="eyebrow">Pilihan hari ini</div><h2>Menu Tada</h2></div>
        <span class="muted small">{{ $products->count() }} menu tersedia</span>
    </div>
    @if ($products->isEmpty())
        <div class="empty"><div class="emoji">🍞</div><h2>Menu sedang disiapkan</h2><p class="muted">Cek kembali sebentar lagi, ya.</p></div>
    @else
        <div class="grid">
            @foreach ($products as $product)
                <article class="product">
                    <div class="product-icon" aria-hidden="true">{{ ['🍓','🍫','🍇','🍑','🧀','🍵'][$loop->index % 6] }}</div>
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <div class="product-foot">
                        <div><div class="price">Rp{{ number_format((float) $product->price, 0, ',', '.') }}</div><div class="muted small">{{ $product->stock > 0 ? 'Stok '.$product->stock : 'Stok habis' }}</div></div>
                        <form method="POST" action="{{ route('cart.store', $product) }}">
                            @csrf
                            <button class="btn btn-sm" type="submit" @disabled($product->stock < 1)>{{ $product->stock > 0 ? 'Tambah +' : 'Habis' }}</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
