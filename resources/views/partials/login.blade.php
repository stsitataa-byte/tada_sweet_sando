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