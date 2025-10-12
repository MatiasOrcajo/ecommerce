<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            "capture-visitor" => "App\Http\Middleware\CaptureVisitor",
            "cart-empty" => "App\Http\Middleware\CartEmptyMiddleware",
            "order-success" => "App\Http\Middleware\OrderSuccessMiddleware",
            "set-cookie-unique-visitant" => "App\Http\Middleware\VisitCookie",
            "register-unique-visitant" => "App\Http\Middleware\TrackUniqueVisit",

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
