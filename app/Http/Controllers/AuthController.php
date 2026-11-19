<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth');
    }

    public function showLogin(): RedirectResponse
    {
        return redirect()->to(route('home').'#login');
    }

    public function showRegister(): RedirectResponse
    {
        return redirect()->to(route('home').'#register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validateWithBag('login', [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi tidak cocok.'], 'login')
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($request->user()->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('register', [
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'first_name' => ['sometimes', 'required', 'string', 'max:60'],
            'last_name' => ['nullable', 'string', 'max:60'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email'],
            'register_email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
            'register_password' => ['sometimes', 'required', 'string', 'min:8', 'max:100'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'register_email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'register_password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $email = $data['email'] ?? $data['register_email'];
        $password = $data['password'] ?? $data['register_password'];
        $name = $data['name'] ?? trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat. Selamat berbelanja!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil keluar.');
    }
}
