<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class OrderSuccessMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $code = $request->route('code');

        if (Session::get("email_validated_$code") === true) {
            return $next($request);
        }

        // Si viene del formulario con el email
        if ($request->isMethod('post') && $request->has('email')) {

            $order = Order::where('code', $code)->first();

            if ($order && strtolower($order->customer->email) === strtolower(trim($request->input('email')))) {

                Session::put("email_validated_$code", true);
                return redirect()->route('order-success', $code); // volver a la misma ruta para reintentar con sesión válida
            }

            return redirect()->route('order-success', $code)->with('error', 'Los datos ingresados no coinciden con ninguna orden registrada.');
        }

        return \response()->view('checkout.order-success-middleware', compact('code'));
    }
}
