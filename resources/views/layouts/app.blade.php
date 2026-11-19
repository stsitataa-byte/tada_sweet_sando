<!DOCTYPE html>
<html lang="id">
<head>
<<<<<<< HEAD
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TADA SWEETSANDO')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Antonio:wght@300;400;700&family=Open+Sans:wght@400;600;700&family=Space+Mono:wght@400;700&family=Titan+One&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}">
</head>
<body class="@yield('body_class')">

    @include('partials.icons')
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.social')
    @include('partials.footer')
    @include('partials.cart')
    @include('partials.game')

    <script>
        window.TADA = {
            csrf: @json(csrf_token()),
            cart: @json(\App\Support\Cart::summary()),
            catalog: @json(\App\Support\Cart::catalog()),
            routes: {
                add: @json(route('cart.add')),
                update: @json(route('cart.update')),
                remove: @json(route('cart.remove')),
                checkout: @json(route('checkout')),
                shop: @json(route('shop'))
            }
        };
    </script>
    <script src="{{ asset('js/app.js') }}?v={{ @filemtime(public_path('js/app.js')) }}"></script>
    <script src="{{ asset('js/game.js') }}?v={{ @filemtime(public_path('js/game.js')) }}"></script>
    @stack('scripts')
=======
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'tada sweetsando — Fresh Fruit Sando')</title>
<meta name="description" content="Fruit sando segar dengan krim lembut dan roti susu empuk: Mango, Strawberry, Grape, dan Mix.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;700&family=Abril+Fatface&family=Barlow+Condensed:wght@500;600&family=Rye&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="@yield('body_class')">
@include('partials.header')
<main>@yield('content')</main>
@include('partials.footer')
@include('partials.cart')
@include('partials.login')
<div class="toast" id="toast" role="status"></div>

<script>
@php
    $sandoData = [
        'products' => $products ?? [],
        'freeShipping' => config('sando.free_shipping', 100000),
        'whatsapp' => config('sando.whatsapp'),
        'productUrl' => url('/product'),
        'current' => $current ?? null,
        'cartDataUrl' => route('cart.contents'),
        'cartUrl' => url('/keranjang'),
        'checkoutUrl' => route('cart.checkout'),
        'isAuthenticated' => auth()->check(),
        'loginError' => $errors->first(),
        'openLogin' => $errors->any() && old('name') === null && old('password_confirmation') === null,
        'openRegister' => $errors->any() && (old('name') !== null || old('password_confirmation') !== null),
    ];
@endphp
    window.SANDO = @json($sandoData);
</script>

<script src="{{ asset('js/art.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
</body>
</html>