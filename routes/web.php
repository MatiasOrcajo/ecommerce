<?php

use App\Http\Controllers\BeaconController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['web']], function () {

    Route::get('/test-carbon', function () {});

    Route::post('/v-beacon', BeaconController::class)->name('v-beacon');

    Route::get('/politicas-devolucion', [\App\Http\Controllers\IndexController::class, 'politics'])->name('politics');

    Route::get('/faqs', [\App\Http\Controllers\IndexController::class, 'faqs'])->name('faqs');

    Route::get('/search', [\App\Http\Controllers\IndexController::class, 'searchProducts'])->name('search');

    Route::get('/search-products', [\App\Http\Controllers\IndexController::class, 'searchProductsAjax'])->name('search-products');

    Route::get('/featured-products', [\App\Http\Controllers\IndexController::class, 'getFeaturedProducts']);

    Route::get('/main-products', [\App\Http\Controllers\IndexController::class, 'getMainProducts']);

    Route::get('/see-cart', [\App\Http\Controllers\CartController::class, 'seeCart']);

    Route::get('/guia-de-talles', function () {

        $categories = \App\Models\Category::all()->toArray();

        return Inertia::render('SizesGuide', ['categories' => $categories]);
    })->name('sizes-guide');

    Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index')->middleware(['set-cookie-unique-visitant']);

    Route::get('/productos/{slug}', [\App\Http\Controllers\ProductController::class, 'show'])->name('product.show')->middleware(['set-cookie-unique-visitant']);

    Route::get('/products/{product}/get-variants', [\App\Http\Controllers\ProductController::class, 'getVariants'])->name('product.variants.show');

    Route::get('/cart', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('cart')->middleware('cart-empty');

    Route::get('/calculate-cart-total-items', [\App\Http\Controllers\CartController::class, 'calculateCartTotalItems']);

    Route::get('/clear-cart', [\App\Http\Controllers\CartController::class, 'clearCart']);

    Route::get('/cart-info', [\App\Http\Controllers\CheckoutController::class, 'getCartInfo'])->name('cart-info');

    Route::get('validate-coupon', [\App\Services\CouponService::class, 'validateCoupon'])->name('validate-coupon');

    /**
     * checkout
     */
    Route::post('/pay', [\App\Http\Controllers\CheckoutController::class, 'pay'])->name('pay');

    Route::get('/orden/{code}', [\App\Http\Controllers\CheckoutController::class, 'processOrderSuccess'])->name('order-success')->middleware('order-success');
    Route::post('/orden/verificar-email/{code}', [\App\Http\Controllers\CheckoutController::class, 'processOrderSuccess'])->name('order.success.verify.email')->middleware('order-success');

    Route::get('/payment-success/{encrypted}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('payment-success');
    Route::get('/payment-failure/{encrypted}', [\App\Http\Controllers\CheckoutController::class, 'failure'])->name('payment-failure');
    Route::get('/payment-pending/{encrypted}', [\App\Http\Controllers\CheckoutController::class, 'pending'])->name('payment-pending');

    Route::get('/consult-preference/{preferenceId}', [\App\Http\Controllers\MercadopagoWebhookController::class, 'handle'])->name('consult-preference');

    Route::delete('/cart', [\App\Http\Controllers\CartController::class, 'deleteProduct']);

    Route::post('/carts/products/{product}', [\App\Http\Controllers\CartController::class, 'addProduct']);
    Route::put('/carts/products/update-quantity', [\App\Http\Controllers\CartController::class, 'updateProductQuantity']);

    // search product pictures by color
    Route::get('/products/{product}/search-pictures-by-color', [\App\Http\Controllers\ProductController::class, 'searchProductImagesByColor']);

    Route::post('/mailing-list-contact', [\App\Http\Controllers\MailingListController::class, 'store'])->name('mailing-list-contact');

});

require __DIR__.'/auth.php';
