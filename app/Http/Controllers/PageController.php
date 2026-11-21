<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;

class PageController extends Controller
{
    public function home()
    {
        return view('home', [
            'catalog' => Cart::catalog(),
            'storeProducts' => Product::query()->where('is_active', true)->orderBy('id')->get(),
        ]);
    }

    public function shop()
    {
        return view('shop', [
            'catalog' => Cart::catalog(),
            'storeProducts' => Product::query()->where('is_active', true)->orderBy('id')->get(),
        ]);
    }

    public function about()
    {
        return view('about', ['catalog' => Cart::catalog()]);
    }

    /** Halaman menu tiga kolom. /menu = varian pertama, /menu/{slug} = varian tertentu. */
    public function product(?string $slug = null)
    {
        $catalog = Cart::catalog();

        if (empty($catalog)) {
            abort(404);
        }

        $slug = $slug ?? array_key_first($catalog);

        if (! isset($catalog[$slug])) {
            abort(404);
        }

        return view('product', [
            'catalog' => $catalog,
            'current' => $catalog[$slug],
        ]);
    }
}
