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
        return redirect()->to(route('home') . '#login');
    }

    public function showLogin()
    {
        return redirect()->to(route('home') . '#login');
    }

    public function showRegister()
    {
        return redirect()->to(route('home') . '#register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validateWithBag('login', [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (!Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'Email atau kata sandi tidak cocok.',
                ], 'login')
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        // Periksa apakah pengguna merupakan admin
        if ($request->user()->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function register(Request $request)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['nullable', 'string', 'max:60'],
            'register_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'register_password' => [
                'required',
                'string',
                'min:8',
                'max:100'
            ],
        ];

        if ($request->has('name') || $request->has('email')) {
            $rules = [
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'max:100', 'confirmed'],
            ];
        }

        $data = $request->validateWithBag('register', $rules, [
            'first_name.required' => 'Nama depan wajib diisi.',
            'register_email.required' => 'Email wajib diisi.',
            'register_email.email' => 'Masukkan alamat email yang valid.',
            'register_email.unique' => 'Email ini sudah terdaftar.',
            'register_password.required' => 'Password wajib diisi.',
            'register_password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = new User();

        if (isset($data['name'])) {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
        } else {
            $user->name = trim(
                $data['first_name'] . ' ' . ($data['last_name'] ?? '')
            );
            $user->email = $data['register_email'];
            $user->password = Hash::make($data['register_password']);
        }

        $user->save();

        // Login otomatis setelah berhasil mendaftar
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Akun berhasil dibuat. Selamat berbelanja!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda berhasil keluar.');
    }
}
