<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['api']], function () {
    Route::get('/sales', [\App\Http\Controllers\DashboardController::class, 'getSales']);

    Route::get('/sales/secondary-info', [\App\Http\Controllers\DashboardController::class, 'getSales']);

    Route::get('/visitors', [\App\Http\Controllers\DashboardController::class, 'getVisitors']);

    Route::get('/list-products', [\App\Http\Controllers\Admin\ProductController::class, 'listProducts']);

    Route::get('/list-orders-list', [\App\Http\Controllers\Admin\PanelController::class, 'listOrderList']);

    Route::get('/list-categories', [\App\Http\Controllers\Admin\CategoryController::class, 'listCategories']);

    Route::get('/categories/{category}/products', [\App\Http\Controllers\Admin\CategoryController::class, 'listProducts'])->name('admin.categories.listProducts');

    Route::post('/orders/{order}/update-status', [\App\Http\Controllers\Admin\PanelController::class, 'updateOrderStatus'])->name('admin.orders.updateStatus');

    Route::get('/products/{product}/list-sizes', [\App\Http\Controllers\Admin\ProductController::class, 'listSizes'])->name('admin.products.listSizes');

    Route::put('/products/{product}/update-size-stock/{productSize}', [\App\Http\Controllers\Admin\ProductController::class, 'updateSizeStock'])->name('admin.products.updateSizeStock');

    Route::bind('product', function ($value) {
        return Product::findOrFail($value);
    });




});
