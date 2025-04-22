<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {

    Route::get('/cart', function (\Illuminate\Http\Request $request) {
        return view('checkout');
    })->name('home')->middleware('cart-empty'); //cart-empty should redirect to home

    Route::get('/cart/{product}', [\App\Http\Controllers\CartControler::class, 'addProduct']);

    Route::get('/clear-cart', [\App\Http\Controllers\CartControler::class, 'clearCart']);

    Route::get('/get-ip-address', [\App\Http\Controllers\VisitorController::class, 'getIpAddress']);

    Route::get('/cart-info', [\App\Http\Controllers\CheckoutController::class, 'getCartInfo'])->name('cart-info');

    Route::get('validate-coupon', [\App\Services\CouponService::class, 'validateCoupon'])->name('validate-coupon');

    Route::post('/pay', [\App\Http\Controllers\CheckoutController::class, 'pay'])->name('pay');

    Route::get('payment-success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('payment-success');
    Route::get('payment-failure/{order}', [\App\Http\Controllers\CheckoutController::class, 'failure'])->name('payment-failure');
    Route::get('payment-pending/{order}', [\App\Http\Controllers\CheckoutController::class, 'pending'])->name('payment-pending');
    Route::post('/mercadopago-notification-endpoint', [\App\Http\Controllers\MercadopagoWebhookController::class, 'handle'])->name('mercadopago-notification-endpoint');
    Route::get('/consult-preference/{preferenceId}', [\App\Http\Controllers\MercadopagoWebhookController::class, 'handle'])->name('consult-preference');

    Route::delete('/cart/{product}', [\App\Http\Controllers\CartControler::class, 'deleteProduct']);


});


Route::prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard
        ');
        })->name('admin.dashboard');

        Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products');

        Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');

        Route::get('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.product.show');

        Route::post('/pictures/{product}', [\App\Http\Controllers\Admin\PictureController::class, 'store'])->name('admin.pictures.store');

        Route::delete('/pictures/{picture}', [\App\Http\Controllers\Admin\PictureController::class, 'destroy'])->name('admin.pictures.destroy');

        Route::get('/pictures/{product}', [\App\Http\Controllers\Admin\PictureController::class, 'getPictures'])->name('admin.pictures.product');

        Route::put('/pictures/{product}/edit-order', [\App\Http\Controllers\Admin\PictureController::class, 'editOrder'])->name('admin.pictures.edit.order');

        Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');

        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories');

        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');

        Route::get('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.categories.show');

    });
});


require __DIR__ . '/auth.php';
