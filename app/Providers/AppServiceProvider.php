<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer(['partials.header', 'partials.cart'], function ($view) {
            $cart = session('cart', []);

            $items = Product::whereIn('id', array_keys($cart))->get()->map(fn($p) => (object) [
                'product'  => $p,
                'qty'      => $cart[$p->id],
                'subtotal' => $p->price * $cart[$p->id],
            ]);

            $view->with('cartItems', $items)
                 ->with('cartCount', array_sum($cart))
                 ->with('cartTotal', $items->sum('subtotal'));
        });
    }
}