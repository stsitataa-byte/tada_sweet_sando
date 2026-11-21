<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /** GET /cart */
    public function show(): JsonResponse
    {
        return response()->json($this->cartPayload());
    }

    /** POST /cart/add (server-side cart API) */
    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'slug' => ['required', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:50'],
            'qty'  => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::query()
            ->where('slug', $data['slug'])
            ->orWhere('slug', str_replace('-', '_', $data['slug']))
            ->first();

        if (! $product || ! $this->isAvailable($product)) {
            return response()->json(['message' => 'Produk ini sedang tidak tersedia.'], 422);
        }

        $cart = $request->session()->get('cart', []);
        $qty = (int) ($cart[$product->id] ?? 0) + (int) $data['qty'];

        if ($qty > $product->stock) {
            return response()->json(['message' => 'Stok tidak mencukupi.'], 422);
        }

        $cart[$product->id] = $qty;
        $request->session()->put('cart', $cart);

        return response()->json($this->cartPayload());
    }

    /** PATCH /cart/update */
    public function update(Request $request): JsonResponse
    {
        $cart = $request->session()->get('cart', []);

        if ($request->filled('key')) {
            $key = (string) $request->input('key');
            $slug = Str::before($key, '|');
            $product = Product::query()->where('slug', $slug)->first();
            if (! $product) {
                return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
            }
            $qty = (int) $request->input('qty', 0);
        } else {
            $data = $request->validate([
                'quantity' => ['required', 'integer', 'min:0', 'max:99'],
            ]);
            $qty = (int) $data['quantity'];
        }

        if (isset($product)) {
            $productId = $product->id;
        } elseif ($request->route('product')) {
            $routeProduct = $request->route('product');
            $product = $routeProduct instanceof Product
                ? $routeProduct
                : Product::find($routeProduct);
            if (! $product) {
                return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
            }
            $productId = $product->id;
        } else {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        if ($qty > $product->stock) {
            return response()->json(['message' => 'Stok tidak mencukupi.'], 422);
        }

        if ($qty <= 0) {
            unset($cart[$productId]);
        } elseif (! isset($cart[$productId])) {
            $cart[$productId] = min($qty, 99);
        } else {
            $cart[$productId] = min($qty, 99);
        }

        $request->session()->put('cart', $cart);
        return response()->json($this->cartPayload());
    }

    /** DELETE /cart/remove */
    public function remove(Request $request): JsonResponse
    {
        $cart = $request->session()->get('cart', []);

        $key = (string) $request->input('key', '');
        $slug = Str::before($key, '|');
        $product = Product::query()->where('slug', $slug)->first();

        if ($product) {
            unset($cart[$product->id]);
        }

        $request->session()->put('cart', $cart);

        return response()->json($this->cartPayload());
    }

    public function index(Request $request)
    {
        return redirect()->to(route('home').'#cart');
    }

    public function contents(Request $request): JsonResponse
    {
        return response()->json($this->cartPayload());
    }

    public function store(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);
        $quantity = (int) ($data['quantity'] ?? 1);

        if (! $this->isAvailable($product) || $quantity > $product->stock) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Stok tidak mencukupi.'], 422);
            }
            return redirect()->route('home')
                ->withErrors(['cart' => 'Stok tidak mencukupi.']);
        }

        $cart = $request->session()->get('cart', []);
        $next = (int) ($cart[$product->id] ?? 0) + $quantity;
        if ($next > $product->stock) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Stok tidak mencukupi.'], 422);
            }
            return redirect()->route('home')
                ->withErrors(['cart' => 'Stok tidak mencukupi.']);
        }

        $cart[$product->id] = $next;
        $request->session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json($this->cartPayload());
        }
        return redirect()->to(route('home').'#cart');
    }

    public function destroy(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json($this->cartPayload());
        }
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $checkout = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
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
                if (! $this->isAvailable($product) || $quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->name} tidak mencukupi. Perbarui jumlah di keranjang.",
                    ]);
                }
                $total += $product->price * $quantity;
            }

            $order = Order::create([
                'user_id' => $request->user()?->id,
                'order_code' => 'TADA-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6)),
                'customer_name' => $checkout['customer_name'],
                'customer_email' => $request->user()?->email,
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

        if (! $request->user()) {
            return redirect()->route('home')->with('success', "Pesanan #{$order->id} berhasil dibuat.");
        }

        return redirect()->route('orders.mine')->with('success', "Pesanan #{$order->id} berhasil dibuat.");
    }

    private function cartPayload(): array
    {
        $cart = session('cart', []);
        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (int $quantity, int|string $id) use ($products): ?array {
                $product = $products->get($id);

                return $product ? [
                    'slug' => $product->slug,
                    'qty' => $quantity,
                    'name' => $product->name,
                    'price' => (int) $product->price,
                    'subtotal' => (int) $product->price * $quantity,
                    'image' => $product->image ?? null,
                ] : null;
            })
            ->filter()
            ->values()
            ->all();

        $total = collect($items)->sum('subtotal');

        return [
            'items' => $items,
            'count' => collect($items)->sum('qty'),
            'total' => $total,
        ];
    }

    private function cartItems(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)->map(function (int $quantity, int|string $id) use ($products) {
            $product = $products->get($id);

            return $product ? [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => (int) $product->price * $quantity,
            ] : null;
        })->filter()->values();

        $total = $items->sum('subtotal');

        return compact('items', 'total');
    }

    private function isAvailable(Product $product): bool
    {
        return (bool) ($product->getAttribute('is_active') ?? true);
    }
}
