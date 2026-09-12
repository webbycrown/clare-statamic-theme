<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\StorePreferenceController;
use Illuminate\Support\Facades\Route;

Route::statamic('/categories/{category_slug}', 'category');
Route::statamic('/cart', 'cart');

Route::post('/cart/add', [CartController::class, 'add'])->name('clare.cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('clare.cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('clare.cart.remove');

Route::get('/locale/{locale}', [StorePreferenceController::class, 'locale'])->name('clare.locale');
Route::get('/currency/{currency}', [StorePreferenceController::class, 'currency'])->name('clare.currency');

Route::get('/clare/dismiss/{kind}', function (string $kind) {
    $name = $kind === 'cookie' ? 'clare_cookie' : 'clare_newsletter';
    return response('ok')->cookie($name, '1', 60 * 24 * 365, '/', null, false, false, false, 'Lax');
})->where('kind', 'cookie|newsletter');
