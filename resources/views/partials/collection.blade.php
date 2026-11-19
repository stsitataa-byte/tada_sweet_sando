{{-- KOLEKSI PRODUK: 4 kartu berwarna. Butuh variabel $catalog. --}}
<section class="collection" id="collection">
    <div class="collection__head">
        <h2 class="display-title">OUR FLAVORS</h2>
        <a class="pill pill--orange" href="{{ route('shop') }}">Shop all</a>
    </div>

    <div class="collection__row">
        @foreach($catalog as $p)
            <a class="flavor-card" href="{{ route('product', ['slug' => $p['slug']]) }}" style="--card: {{ $p['color'] }}; --accent: {{ $p['accent'] }}">
                <div class="flavor-card__top">
                    <b>{{ $p['name'] }}</b>
                    <span>{{ $p['tagline'] ?? '' }}</span>
                </div>
                <div class="flavor-card__photo">
                    @include('partials.img', ['src' => $p['image'], 'alt' => 'Fruit sando ' . $p['name'], 'class' => 'flavor-card__img'])
                </div>
                <div class="flavor-card__price">
                    {{ $p['price_from'] ? 'Mulai ' . $p['price_from'] : 'Harga belum diisi' }}
                </div>
                <div class="flavor-card__btn">VIEW SANDO <svg width="16" height="16"><use href="#i-arrow"/></svg></div>
            </a>
        @endforeach
    </div>
</section>
