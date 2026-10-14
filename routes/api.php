<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CheckoutOrderController;
use Illuminate\Support\Facades\Route;

// POST /api/checkout: hanya menghitung, tanpa menyimpan database.
Route::post('/checkout', [CheckoutController::class, 'api'])->name('api.checkout');

// POST /api/orders: menghitung di server lalu menyimpan ke database.
Route::post('/orders', [CheckoutOrderController::class, 'storeApi'])->name('api.orders.store');
Route::get('/orders/{order}', [CheckoutOrderController::class, 'showApi'])
    ->whereNumber('order')->name('api.orders.show');
