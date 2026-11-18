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