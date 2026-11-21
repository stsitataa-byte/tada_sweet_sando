@extends('layouts.store')

@section('title', 'Masuk')

@section('content')
    <section class="narrow auth-card">
        <div class="eyebrow">Senang bertemu lagi</div><h1>Masuk ke Tada</h1><p class="muted">Login bersifat opsional. Semua pelanggan tetap bisa memesan tanpa akun.</p>
        <form method="POST" action="{{ route('login') }}">@csrf
            <div class="field"><label for="email">Email</label><input class="input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus></div>
            <div class="field"><label for="password">Kata sandi</label><input class="input" id="password" name="password" type="password" autocomplete="current-password" required></div>
            <label class="row small muted" style="margin:0 0 17px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
            <button class="btn" style="width:100%" type="submit">Masuk</button>
        </form>
        <p class="muted small" style="margin-bottom:0">Belum punya akun? <a class="price" href="{{ route('register') }}">Daftar di sini</a></p>
    </section>
@endsection
