<?php
namespace App\Http\Controllers;

use App\Support\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    /** GET /cart  -> isi keranjang (JSON) */
    public function show(): JsonResponse
    {
        return response()->json(Cart::summary());
    }

    /** POST /cart/add */
    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'slug' => ['required', 'string', Rule::in(array_keys(Cart::products()))],
            'size' => ['required', 'string', Rule::in(array_keys(Cart::SIZES))],
            'qty'  => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Cart::product($data['slug']);

        if (! ($product['available'] ?? true)) {
            return response()->json(['message' => 'Produk ini sedang tidak tersedia.'], 422);
        }

        // Harga SELALU dari server (config/sando.php), bukan dari browser.
        if (Cart::priceFor($product, $data['size']) === null) {
            return response()->json(['message' => 'Harga produk ini belum diisi oleh penjual.'], 422);
        }

        Cart::add($data['slug'], $data['size'], (int) $data['qty']);

        return response()->json(Cart::summary());
    }

    /** PATCH /cart/update  (qty 0 = hapus item) */
    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:100'],
            'qty' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        Cart::setQty($data['key'], (int) $data['qty']);

        return response()->json(Cart::summary());
    }

    /** DELETE /cart/remove */
    public function remove(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:100'],
        ]);

        Cart::remove($data['key']);

        return response()->json(Cart::summary());
    }
}  