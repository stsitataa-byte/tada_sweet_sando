<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth');
    }

    public function login(Request $request)
    {
        $credentials = $request->validateWithBag('login', [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'], 'login')
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate(); // keranjang di session tetap aman

        return redirect()->intended(route('home'));
    }

    public function register(Request $request)
    {
        $data = $request->validateWithBag('register', [
            'first_name'      => ['required', 'string', 'max:60'],
            'last_name'       => ['nullable', 'string', 'max:60'],
            'register_email'  => ['required', 'email', 'max:255', 'unique:users,email'],
            'register_password' => ['required', 'string', 'min:8', 'max:100'],
        ], [
            'register_email.unique' => 'Email ini sudah terdaftar.',
            'register_password.min' => 'Password minimal 8 karakter.',
        ]);

        $user           = new User();
        $user->name     = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));
        $user->email    = $data['register_email'];
        $user->password = Hash::make($data['register_password']);
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}