<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CheckoutOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'web'])->name('checkout.calculate');

Route::get('/orders', [CheckoutOrderController::class, 'index'])->name('orders.index');
Route::post('/orders', [CheckoutOrderController::class, 'storeWeb'])->name('orders.store');
