<<<<<<< HEAD
{{-- LOGIN + CREATE ACCOUNT berdampingan. Memakai tabel users bawaan Laravel. --}}
<section class="auth">
    {{-- LOGIN --}}
    <div class="auth__col">
        <h1 class="auth__title auth__title--yellow">LOG IN</h1>
        <p>Welcome back!</p>

        <form method="POST" action="{{ route('login.attempt') }}" novalidate>
            @csrf
            <label for="login_email">Email address</label>
            <input id="login_email" type="email" name="email" value="{{ old('email') }}" placeholder="EMAIL ADDRESS" autocomplete="email" required>
            @if($errors->login->has('email'))<p class="field-error">{{ $errors->login->first('email') }}</p>@endif

            <label for="login_password">Password</label>
            <input id="login_password" type="password" name="password" placeholder="PASSWORD" autocomplete="current-password" required>
            @if($errors->login->has('password'))<p class="field-error">{{ $errors->login->first('password') }}</p>@endif

            <button class="btn btn--outline btn--block" type="submit">LOG IN</button>
        </form>
    </div>

    {{-- CREATE ACCOUNT --}}
    <div class="auth__col">
        <h2 class="auth__title auth__title--lilac">CREATE ACCOUNT</h2>
        <p>Create an account to access your profile info and more.</p>

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <div class="auth__two">
                <div>
                    <label for="first_name">First name</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="FIRST NAME" autocomplete="given-name" required>
                    @if($errors->register->has('first_name'))<p class="field-error">{{ $errors->register->first('first_name') }}</p>@endif
                </div>
                <div>
                    <label for="last_name">Last name</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="LAST NAME" autocomplete="family-name">
                </div>
            </div>

            <label for="register_email">Email address</label>
            <input id="register_email" type="email" name="register_email" value="{{ old('register_email') }}" placeholder="EMAIL ADDRESS" autocomplete="email" required>
            @if($errors->register->has('register_email'))<p class="field-error">{{ $errors->register->first('register_email') }}</p>@endif

            <label for="register_password">Password</label>
            <input id="register_password" type="password" name="register_password" placeholder="PASSWORD (MIN. 8)" autocomplete="new-password" required>
            @if($errors->register->has('register_password'))<p class="field-error">{{ $errors->register->first('register_password') }}</p>@endif

            <button class="btn btn--outline btn--block" type="submit">CREATE ACCOUNT</button>
        </form>
    </div>
</section>
=======
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
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
