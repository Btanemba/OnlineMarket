<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/category/{id}', [FrontendController::class, 'category'])->name('category');
Route::get('/product/{id}', [FrontendController::class, 'product'])->name('product');

// Order Tracking
Route::get('/track-order', [FrontendController::class, 'trackOrder'])->name('track.order');
Route::post('/track-order', [FrontendController::class, 'searchOrder'])->name('track.order.search');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout/place-order', [CartController::class, 'placeOrder'])->name('checkout.place-order');
Route::get('/order/confirmation/{id}', [CartController::class, 'orderConfirmation'])->name('order.confirmation');
