<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {
    return view('welcome');
});


Route::group(['middleware' => ['web']], function () {
    Route::get('/cart/{product}', [\App\Http\Controllers\CartsController::class, 'addProduct']);
    Route::get('/clear-cart', [\App\Http\Controllers\CartsController::class, 'clearCart']);
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/pagar', [\App\Http\Controllers\CheckoutController::class, 'pagar'])->name('pagar');

Route::get('pago-exitoso',[\App\Http\Controllers\CheckoutController::class, 'success'])->name('pago-exitoso');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
