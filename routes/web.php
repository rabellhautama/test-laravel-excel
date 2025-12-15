<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile (hanya untuk user login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Order
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Cart + Checkout
    Route::post('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::resource('cart', CartController::class);
});

// Callback pembayaran Midtrans
Route::post('/payment/midtrans-callback', [PaymentController::class, 'midtransCallback']);

// Checkout order (jika ada route khusus di OrderController)
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

// Product
Route::get('/list-product', [ProductController::class, 'search'])->name('products.search');
Route::get('/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/store', [ProductController::class, 'store'])->name('products.store');

// Filter products by radius
Route::get('/products', function (Request $request) {
    $latitude = $request->input('latitude');
    $longitude = $request->input('longitude');
    $radius = $request->input('radius', 10);

    $products = Product::select('*')
        ->selectRaw(
            "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) -
            radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
            [$latitude, $longitude, $latitude]
        )
        ->having('distance', '<=', $radius)
        ->orderBy('distance', 'asc')
        ->get();

    return response()->json($products);
});

require __DIR__.'/auth.php';
