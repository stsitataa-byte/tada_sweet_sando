<div class="modal" id="loginM" aria-hidden="true">
  <form class="mbox" id="loginF" method="POST" action="{{ route('login.submit') }}">
    @csrf
    <button type="button" class="mx" data-close aria-label="Tutup">✕</button>
    <h2>LOG IN</h2>
    <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></label>
    <label>Password<input type="password" name="password" autocomplete="current-password" required></label>
    <label class="remember"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
    <p class="err" id="loginErr">{{ $errors->first() }}</p>
    <button class="btn full" type="submit">LOG IN</button>
    <a href="{{ route('register') }}" class="reg" data-show-register>Belum punya akun? Daftar</a>
  </form>
</div>
<div class="modal" id="registerM" aria-hidden="true">
  <form class="mbox" method="POST" action="{{ route('register.submit') }}">
    @csrf
    <button type="button" class="mx" data-close aria-label="Tutup">✕</button>
    <h2>BUAT AKUN</h2>
    <label>Nama<input type="text" name="name" value="{{ old('name') }}" autocomplete="name" required></label>
    <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></label>
    <label>Password<input type="password" name="password" autocomplete="new-password" minlength="8" required></label>
    <label>Ulangi password<input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required></label>
    <p class="err">{{ $errors->first() }}</p>
    <button class="btn full" type="submit">DAFTAR</button>
    <a href="{{ route('login') }}" class="reg" data-show-login>Sudah punya akun? Log in</a>
  </form>
</div>