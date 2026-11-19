<?php

namespace App\Http\Controllers;

use App\Support\Cart;

class PageController extends Controller
{
    public function home()
    {
        return view('home', ['catalog' => Cart::catalog()]);
    }

    public function shop()
    {
        return view('shop', ['catalog' => Cart::catalog()]);
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
