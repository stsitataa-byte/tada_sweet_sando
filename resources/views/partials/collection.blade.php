<<<<<<< HEAD
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
=======

<section class="coll">
    <div class="scroller" id="scroller">

        @foreach ($products as $slug => $p)
            <article
                class="pcard rv"
                style="--head: {{ $p['head'] }}; --ac: {{ $p['accent'] }}">

                <div class="pc-h">
                    <span class="mono">TADA SWEETSANDO</span>
                    <h3>{{ $p['name'] }}</h3>
                    <p>{{ $p['blurb'] }}</p>
                </div>

                <a
                    class="pc-i"
                    href="{{ route('product', $p['slug']) }}"
                    data-art="scene:{{ $p['slug'] }}:0"
                    aria-label="{{ $p['name'] }}">
                </a>

                <div class="pc-info">
                    <span class="pc-price">
                        Rp {{ number_format($p['price'], 0, ',', '.') }}
                    </span>

                    <a
                        class="btn full ac"
                        href="{{ route('product', $p['slug']) }}">
                        LIHAT PRODUK ↗
                    </a>
                </div>

            </article>
        @endforeach

    </div>

    <div class="center">
        <a class="btn" href="{{ route('shop') }}">
            LIHAT SEMUA PRODUK
        </a>
    </div>
</section>
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
