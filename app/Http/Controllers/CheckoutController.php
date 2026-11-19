<?php

namespace App\Http\Controllers;

use App\Support\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show()
    {
        return view('checkout', [
            'cart'      => Cart::summary(),
            'confirmed' => false,
            'order'     => null,
            'waUrl'     => null,
        ]);
    }

    public function store(Request $request)
    {
        $cart = Cart::summary();

        if (empty($cart['items'])) {
            return redirect()->route('shop')->with('notice', 'Keranjang masih kosong.');
        }

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'phone'   => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'address' => ['required', 'string', 'max:500'],
            'notes'   => ['nullable', 'string', 'max:500'],
        ], [
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka (8-20 digit).',
        ]);

        // Ringkasan dibuat dari isi keranjang di server, bukan dari form.
        $lines = [];
        foreach ($cart['items'] as $item) {
            $lines[] = sprintf('- %s %s x%d = %s', $item['name'], $item['size_label'], $item['qty'], $item['subtotal_text']);
        }

        $message = "Halo TADA SWEETSANDO, saya ingin memesan:\n"
            . implode("\n", $lines)
            . "\nTotal: " . $cart['total_text']
            . "\n\nNama: " . $data['name']
            . "\nTelepon: " . $data['phone']
            . "\nAlamat: " . $data['address']
            . (! empty($data['notes']) ? "\nCatatan: " . $data['notes'] : '');

        $number = preg_replace('/\D/', '', (string) config('sando.whatsapp'));
        $waUrl  = $number ? 'https://wa.me/' . $number . '?text=' . rawurlencode($message) : null;

        return view('checkout', [
            'cart'      => $cart,
            'confirmed' => true,
            'order'     => $data,
            'waUrl'     => $waUrl,
        ]);
    }
}