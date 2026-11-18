
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
