<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (int $quantity, int|string $id) use ($products) {
                $product = $products->get($id);

                return $product ? [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ] : null;
            })
            ->filter()
            ->values();

        return view('shop.cart', [
            'items' => $items,
            'total' => $items->sum('subtotal'),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ], [
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah maksimal 99.',
        ]);

        if (! $product->is_active || $product->stock < 1) {
            return back()->withErrors(['cart' => 'Produk sedang habis atau tidak tersedia.']);
        }

        $cart = $request->session()->get('cart', []);
        $quantity = ($cart[$product->id] ?? 0) + ($data['quantity'] ?? 1);

        if ($quantity > $product->stock) {
            return back()->withErrors(['cart' => 'Jumlah di keranjang melebihi stok yang tersedia.']);
        }

        $cart[$product->id] = $quantity;
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ], [
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah maksimal 99.',
        ]);

        if (! $product->is_active || $data['quantity'] > $product->stock) {
            return back()->withErrors(['cart' => 'Jumlah melebihi stok produk yang tersedia.']);
        }

        $cart = $request->session()->get('cart', []);
        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id] = $data['quantity'];
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $checkout = $request->validate([
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_address' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'in:COD,QRIS'],
        ], [
            'customer_phone.required' => 'Nomor telepon wajib diisi untuk pesanan.',
            'customer_phone.max' => 'Nomor telepon maksimal 30 karakter.',
            'customer_address.max' => 'Alamat maksimal 2000 karakter.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ]);
        $cart = $request->session()->get('cart', []);

        if ($cart === []) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $order = DB::transaction(function () use ($cart, $request, $checkout) {
            $products = Product::query()
                ->whereIn('id', array_keys($cart))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($cart)) {
                throw ValidationException::withMessages([
                    'cart' => 'Ada produk yang sudah tidak tersedia. Perbarui keranjang Anda.',
                ]);
            }

            $total = 0;
            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);
                if (! $product->is_active || $quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->name} tidak mencukupi. Perbarui jumlah di keranjang.",
                    ]);
                }
                $total += $product->price * $quantity;
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_code' => 'TADA-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6)),
                'customer_name' => $request->user()->name,
                'customer_email' => $request->user()->email,
                'customer_phone' => $checkout['customer_phone'],
                'customer_address' => $checkout['customer_address'] ?? null,
                'subtotal' => $total,
                'shipping_cost' => 0,
                'total' => $total,
                'payment_method' => $checkout['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);

            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'product_price' => $product->price,
                    'subtotal' => $product->price * $quantity,
                ]);
                $product->decrement('stock', $quantity);
            }

            return $order;
        });

        $request->session()->forget('cart');

        return redirect()->route('orders.mine')->with('success', "Pesanan #{$order->id} berhasil dibuat.");
    }
}
