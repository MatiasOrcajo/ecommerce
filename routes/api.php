<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api']], function () {
    Route::post('/mercadopago-notification-endpoint', [\App\Http\Controllers\MercadopagoWebhookController::class, 'handle'])->name('mercadopago-notification-endpoint');

    Route::bind('product', function ($value) {
        return Product::findOrFail($value);
    });

});
