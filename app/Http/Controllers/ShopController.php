<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        return view('shop.index', [
            'products' => Product::query()->where('is_active', true)->orderBy('id')->get(),
        ]);
    }

    public function orders(Request $request): View
    {
        return view('shop.orders', [
            'orders' => Order::query()
                ->with('items')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->get(),
        ]);
    }
}
