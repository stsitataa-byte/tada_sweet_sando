@extends('layouts.store')

@section('title', 'Daftar')

@section('content')
    <section class="narrow auth-card">
        <div class="eyebrow">Satu langkah menuju sando favorit</div><h1>Buat akun Tada</h1><p class="muted">Daftar untuk memantau pesananmu. Belum ingin daftar? Pesanan tetap bisa dibuat.</p>
        <form method="POST" action="{{ route('register') }}">@csrf
            <div class="field"><label for="name">Nama</label><input class="input" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="120" required autofocus></div>
            <div class="field"><label for="email">Email</label><input class="input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></div>
            <div class="field"><label for="password">Kata sandi (minimal 8 karakter)</label><input class="input" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required></div>
            <div class="field"><label for="password_confirmation">Ulangi kata sandi</label><input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required></div>
            <button class="btn" style="width:100%" type="submit">Buat akun</button>
        </form>
        <p class="muted small" style="margin-bottom:0">Sudah punya akun? <a class="price" href="{{ route('login') }}">Masuk</a></p>
    </section>
@endsection
