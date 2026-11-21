<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/* Halaman */
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/shop', [PageController::class, 'shop'])->name('shop');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/menu/{slug?}', [PageController::class, 'product'])->name('product');

/* Keranjang (session) */
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update.key');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

/* Checkout */
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

/* Login / Register / Logout */
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/masuk', [AuthController::class, 'login'])->name('login.alias');
Route::post('/daftar', [AuthController::class, 'register'])->name('register.alias');
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout.alias');

Route::get('/shop-menu', [ShopController::class, 'index'])->name('shop.index');
Route::get('/keranjang/data', [CartController::class, 'contents'])->name('cart.contents');
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/pesanan-saya', [ShopController::class, 'orders'])->middleware('auth')->name('orders.mine');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::patch('/produk/{product}/stok', [AdminController::class, 'updateStock'])->name('products.stock');
    Route::post('/arus-kas', [AdminController::class, 'storeCashFlow'])->name('cash-flows.store');
    Route::patch('/pesanan/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
});
