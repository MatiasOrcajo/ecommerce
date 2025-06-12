<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CartEmptyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cart = Session::get('cart');

        if (empty($cart[array_key_first($cart)]) || $cart[array_key_first($cart)]["order_total"] == 0){

            return redirect()->route('index');
        }

        return $next($request);
    }
}
