<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api']], function () {
    Route::get('/sales', [\App\Http\Controllers\DashboardController::class, 'getSales']);

    Route::get('/sales/secondary-info', [\App\Http\Controllers\DashboardController::class, 'getSales']);

    Route::get('/visitors', [\App\Http\Controllers\DashboardController::class, 'getVisitors']);

});
