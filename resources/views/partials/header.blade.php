@php
    $headerCart = \App\Support\Cart::summary();
    $headerCatalog = \App\Support\Cart::catalog();
    $logoSrc = config('sando.images.logo');
    $logoOk = $logoSrc && file_exists(public_path($logoSrc));
@endphp

{{-- ANNOUNCEMENT BAR (teks berjalan) --}}
<div class="announce" aria-label="Promo">
    <div class="announce__track">
        @for($g = 0; $g < 2; $g++)
            <div class="announce__group" @if($g === 1) aria-hidden="true" @endif>
                @for($r = 0; $r < 3; $r++)
                    @foreach(config('sando.announcement', []) as $text)
                        <span>{{ $text }}</span><i>&bull;</i>
                    @endforeach
                @endfor
            </div>
        @endfor
    </div>
</div>

{{-- HEADER --}}
<header class="site-header">
    <div class="site-header__left">
        <div class="shop-wrap">
            <button type="button" class="pill pill--pink" data-shop-btn aria-expanded="false" aria-controls="shopPanel">Shop</button>
            <div class="shop-menu" id="shopPanel">
                <a class="shop-menu__title" href="{{ route('shop') }}">Fruit Sando <svg width="16" height="16"><use href="#i-arrow"/></svg></a>
                <div class="shop-menu__grid">
                    @foreach($headerCatalog as $p)
                        <a class="shop-menu__item" href="{{ route('product', ['slug' => $p['slug']]) }}" style="--card: {{ $p['color'] }}; --n: {{ $loop->index }}">
                            <span>{{ $p['name'] }}</span>
                            @if($p['image_url'])<img src="{{ $p['image_url'] }}" alt="">@endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <a class="pill" href="{{ route('about') }}">About</a>
    </div>

    <a class="brand" href="{{ route('home') }}" aria-label="TADA SWEETSANDO">
        @if($logoOk)
            <img src="{{ asset($logoSrc) }}" alt="TADA SWEETSANDO">
        @else
            <span class="brand__text"><b>tada</b><small>sweetsando</small></span>
        @endif
    </a>

    <div class="site-header__right">
        @auth
            <span class="hello">Hi, {{ \Illuminate\Support\Str::limit(auth()->user()->name, 12) }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="pill" type="submit">Log out</button>
            </form>
        @else
            <a class="pill" href="{{ route('login') }}">Log in</a>
        @endauth

        <button type="button" class="icon-btn" data-game-open aria-label="Buka mini game">
            <svg width="22" height="22"><use href="#i-game"/></svg>
        </button>
        <button type="button" class="icon-btn cart-btn" data-cart-open aria-label="Buka keranjang">
            <svg width="22" height="22"><use href="#i-cart"/></svg>
            <span class="cart-count" data-cart-count>{{ $headerCart['count'] }}</span>
        </button>
    </div>
</header>