<!DOCTYPE html>
<html lang="id">
<head>
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
</body>
</html>