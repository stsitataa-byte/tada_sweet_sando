<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tada Sweet Sando') · Tada Sweet Sando</title>
    <style>
        :root{font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;color:#31271f;background:#fffaf4;font-synthesis:none;text-rendering:optimizeLegibility}
        *{box-sizing:border-box}body{margin:0;line-height:1.55}a{color:inherit;text-decoration:none}button,input,select,textarea{font:inherit}
        .topbar{background:#fff;position:sticky;top:0;z-index:5;border-bottom:1px solid #f1e7dc}.nav{max-width:1160px;margin:auto;padding:14px 24px;display:flex;align-items:center;gap:24px}
        .brand{font-weight:850;font-size:19px;color:#8f402d;letter-spacing:-.04em;margin-right:auto}.brand span{color:#d58d57}.navlinks{display:flex;align-items:center;gap:20px;font-size:14px;color:#66584b}.navlinks a:hover{color:#9e4e34}
        .nav form{margin:0}.wrap{max-width:1100px;margin:0 auto;padding:36px 24px 70px}.narrow{max-width:520px;margin:0 auto}.hero{padding:42px 38px;border-radius:26px;background:#f8eadd;display:grid;grid-template-columns:1.5fr 1fr;gap:20px;align-items:center;margin-bottom:32px;overflow:hidden}
        .eyebrow{font-size:12px;letter-spacing:.14em;text-transform:uppercase;font-weight:750;color:#a55539}.hero h1{font-size:clamp(34px,5vw,54px);line-height:1.05;letter-spacing:-.055em;margin:10px 0 14px;max-width:520px}.hero p{color:#6b594b;max-width:480px;margin:0 0 22px}.hero-art{font-size:110px;text-align:center;filter:drop-shadow(0 14px 12px #9b573533)}
        .section-head{display:flex;align-items:end;justify-content:space-between;gap:20px;margin:34px 0 18px}.section-head h2,.panel h2{margin:0;font-size:24px;letter-spacing:-.035em}.muted{color:#85776b}.small{font-size:13px}.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}.product,.panel,.card,.order-card,.auth-card{background:#fff;border:1px solid #f0e6dc;border-radius:18px;box-shadow:0 8px 25px #603c1208}
        .product{padding:20px;display:flex;flex-direction:column;min-height:228px}.product-icon{height:82px;border-radius:13px;background:#fff3e8;display:grid;place-items:center;font-size:42px;margin-bottom:16px}.product h3{margin:0 0 4px;font-size:17px}.product p{margin:0;color:#837467;font-size:13px;flex:1}.product-foot{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:16px}.price{font-weight:800;color:#9a4b31}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:#9b4c32;color:#fff;border:0;border-radius:10px;padding:10px 15px;font-weight:700;cursor:pointer;transition:background .15s,transform .15s}.btn:hover{background:#7f3925;transform:translateY(-1px)}.btn:disabled{opacity:.55;cursor:not-allowed;transform:none}.btn-light{background:#f6eee7;color:#714c3b}.btn-light:hover{background:#ecded1}.btn-danger{background:#fff0ee;color:#a64135}.btn-danger:hover{background:#ffe3df}.btn-sm{font-size:12px;padding:7px 10px}.inline{display:inline}.flash{padding:12px 16px;border-radius:10px;margin-bottom:18px;background:#e8f4e9;color:#315d3b}.errors{background:#fff0ee;color:#943e32;padding:12px 16px;border-radius:10px;margin-bottom:18px}.errors ul{margin:0;padding-left:20px}
        .panel{padding:22px;margin-bottom:20px}.panel-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px}.panel h2{font-size:20px}.table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse;min-width:570px}th,td{text-align:left;padding:13px 10px;border-bottom:1px solid #f0e9e2;vertical-align:middle}th{font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#8a7b6e}td{font-size:14px}.field{display:grid;gap:6px;margin-bottom:14px}.field label{font-size:13px;font-weight:700;color:#5e4d40}.input,select,textarea{width:100%;padding:10px 12px;border:1px solid #e6d9cd;border-radius:9px;background:#fff;color:#342820;outline:none}.input:focus,select:focus,textarea:focus{border-color:#b66c4e;box-shadow:0 0 0 3px #b66c4e1a}textarea{min-height:72px;resize:vertical}.row{display:flex;align-items:center;gap:10px}.row .input{min-width:82px}.actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}.split{display:grid;grid-template-columns:1fr 1fr;gap:20px}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:22px 0}.card{padding:18px}.card-label{font-size:12px;color:#807267}.card-value{font-size:24px;font-weight:800;letter-spacing:-.04em;margin:5px 0}.card-note{font-size:12px;color:#95877b}.badge{display:inline-flex;padding:4px 9px;border-radius:999px;font-size:11px;font-weight:750;background:#f5eee7;color:#775d48}.status-pending{background:#fff2d6;color:#805b09}.status-processing{background:#e8f1ff;color:#315f9c}.status-completed{background:#e8f4e9;color:#315d3b}.status-cancelled{background:#fff0ee;color:#943e32}
        .order-card{padding:19px;margin-bottom:13px}.order-title{display:flex;justify-content:space-between;gap:12px;align-items:start}.order-items{font-size:13px;color:#796b5e;margin:12px 0}.auth-card{padding:28px}.auth-card h1{margin:0 0 6px;font-size:29px;letter-spacing:-.04em}.empty{text-align:center;padding:56px 20px;background:#fff;border:1px dashed #e6d9cd;border-radius:18px}.empty .emoji{font-size:52px}.footer{text-align:center;color:#9c8e82;font-size:12px;padding:25px;border-top:1px solid #f1e7dc}
        @media(max-width:760px){.nav{padding:12px 16px;flex-wrap:wrap;gap:10px}.navlinks{order:3;width:100%;overflow:auto;gap:16px}.wrap{padding:25px 16px 48px}.hero{grid-template-columns:1fr;padding:30px 24px}.hero-art{display:none}.grid{grid-template-columns:repeat(2,minmax(0,1fr))}.stats{grid-template-columns:repeat(2,1fr)}.split{grid-template-columns:1fr}}
        @media(max-width:460px){.grid{grid-template-columns:1fr}.section-head{align-items:start;flex-direction:column}.product{min-height:190px}.stats{gap:9px}.card{padding:14px}.card-value{font-size:20px}.auth-card{padding:22px}}
    </style>
</head>
<body>
    <header class="topbar">
        <nav class="nav">
            <a class="brand" href="{{ route('home') }}">tada<span>·</span> sweet sando</a>
            <div class="navlinks">
                <a href="{{ route('shop.index') }}">Menu</a>
                <a href="{{ route('cart.index') }}">Keranjang ({{ array_sum(session('cart', [])) }})</a>
                @auth
                    <a href="{{ route('orders.mine') }}">Pesanan saya</a>
                    @if (auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}">Dashboard admin</a>@endif
                    <span class="muted small">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-light btn-sm" type="submit">Keluar</button></form>
                @else
                    <a href="{{ route('login') }}">Masuk (opsional)</a>
                @endauth
            </div>
        </nav>
    </header>
    <main class="wrap">
        @if (session('success'))<div class="flash" role="status">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="errors" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
    </main>
    <footer class="footer">Dibuat dengan sepenuh hati · Tada Sweet Sando</footer>
</body>
</html>
