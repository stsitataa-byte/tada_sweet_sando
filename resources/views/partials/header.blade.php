<<<<<<< HEAD
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
            <button type="button" class="pill pill--pink" data-shop-toggle aria-expanded="false" aria-controls="shopMenu">Shop</button>
            <div class="shop-menu" id="shopMenu" hidden>
                <a class="shop-menu__title" href="{{ route('shop') }}">Fruit Sando <svg width="16" height="16"><use href="#i-arrow"/></svg></a>
                <div class="shop-menu__grid">
                    @foreach($headerCatalog as $p)
                        <a class="shop-menu__item" href="{{ route('product', ['slug' => $p['slug']]) }}" style="--card: {{ $p['color'] }}">
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
=======
@php $t = 'FRESH FRUIT SANDO MADE DAILY • REAL FRUITS • SOFT CREAM • SWEET MOMENTS WITH TADA SWEETSANDO! • '; @endphp
<div class="top">
  <div class="ticker" aria-label="Pengumuman"><div class="ticker-in">
    <span>{{ str_repeat($t, 6) }}</span><span aria-hidden="true">{{ str_repeat($t, 6) }}</span>
  </div></div>
  <header class="hd" id="hd">
    <nav class="hd-l">
      <a class="pill {{ request()->routeIs('shop','product') ? 'on' : '' }}" href="{{ route('shop') }}">SHOP</a>
      <a class="pill {{ request()->routeIs('about') ? 'on' : '' }}" href="{{ route('about') }}">ABOUT</a>
    </nav>
    <a class="logo" href="{{ route('home') }}" aria-label="tada sweetsando">
      <span class="logo-1">tada</span><span class="logo-2">sweetsando</span>
    </a>
    <div class="hd-r">
      @auth
        <a class="pill" href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('orders.mine') }}">{{ auth()->user()->is_admin ? 'ADMIN' : 'PESANAN' }}</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="pill" type="submit">LOG OUT</button></form>
      @else
        <button class="pill" id="loginBtn" type="button">LOG IN</button>
      @endauth
      <button class="pill cart-btn" id="cartBtn" type="button" aria-label="Buka keranjang">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 8h14l-1.2 11H6.2z"/><path d="M9 8a3 3 0 0 1 6 0"/></svg>
        <b id="cartCount">0</b>
      </button>
    </div>
  </header>
  @yield('subnav')
</div>
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
