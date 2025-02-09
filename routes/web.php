<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {


    return view('welcome');
})->middleware('capture-visitor')->name('home');


Route::group(['middleware' => ['web']], function () {
    Route::get('/cart/{product}', [\App\Http\Controllers\CartControler::class, 'addProduct']);
    Route::get('/clear-cart', [\App\Http\Controllers\CartControler::class, 'clearCart']);
    Route::get('/get-ip-address',[\App\Http\Controllers\VisitorController::class, 'getIpAddress']);
    Route::get('/cart-info', [\App\Http\Controllers\CheckoutController::class, 'getCartInfo'])->name('cart-info');
    Route::get('validate-coupon', [\App\Services\CouponService::class, 'validateCoupon'])->name('validate-coupon');

});

Route::post('/pay', [\App\Http\Controllers\CheckoutController::class, 'pay'])->name('pay');

Route::get('pago-exitoso/{order}',[\App\Http\Controllers\CheckoutController::class, 'success'])->name('pago-exitoso');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard
        ');
    })->name('dashboard');

});

require __DIR__.'/auth.php';
