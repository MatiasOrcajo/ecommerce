<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {

    \App\Events\NewVisitor::dispatch();

    return view('welcome');
});


Route::group(['middleware' => ['web']], function () {
    Route::get('/cart/{product}', [\App\Http\Controllers\CartsController::class, 'addProduct']);
    Route::get('/clear-cart', [\App\Http\Controllers\CartsController::class, 'clearCart']);
    Route::get('/get-ip-address',[\App\Http\Controllers\GuestController::class, 'getIpAddress']);
});

Route::post('/pagar', [\App\Http\Controllers\CheckoutController::class, 'pagar'])->name('pagar');

Route::get('pago-exitoso/{order}',[\App\Http\Controllers\CheckoutController::class, 'success'])->name('pago-exitoso');

Route::get('validate-coupon', [\App\Services\CouponService::class, 'validateCoupon'])->name('validate-coupon');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard
        ');
    })->name('dashboard');

});

require __DIR__.'/auth.php';
