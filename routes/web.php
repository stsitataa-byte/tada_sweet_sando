<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/checkout', [CartController::class, 'checkout'])->middleware('auth')->name('cart.checkout');
Route::get('/pesanan-saya', [ShopController::class, 'orders'])->middleware('auth')->name('orders.mine');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::patch('/produk/{product}/stok', [AdminController::class, 'updateStock'])->name('products.stock');
    Route::post('/arus-kas', [AdminController::class, 'storeCashFlow'])->name('cash-flows.store');
    Route::patch('/pesanan/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
});
