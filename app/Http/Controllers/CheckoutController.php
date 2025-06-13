<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\MercadoPagoService;
use App\Traits\CartTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MercadoPago\Client\Preference\PreferenceClient;

class CheckoutController extends Controller
{

    use CartTrait;

    public function __construct(private readonly MercadoPagoService $mpService, private readonly CheckoutService $checkoutService)
    {

    }


    public function index()
    {
        return view('checkout');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $request)
    {

        dd(collect(json_decode($request->data)));

        return $this->mpService->createPreference($request);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function success(Request $request, Order $order)
    {
        $order->status = 'Orden recibida';
        $order->save();

    }


    /**
     * @param Request $request
     * @return void
     */
    public function failure(Request $request, Order $order)
    {
        $order->status = 'Pago rechazado';
        $order->save();

    }



    /**
     * @param Request $request
     * @return void
     */
    public function pending(Request $request, Order $order)
    {
        $order->status = 'Pendiente de aprobación';
        $order->save();

    }

    /**
     * Handle the request to retrieve cart information from the session.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartInfo(): JsonResponse
    {
        $cart = $this->getCart();

        return response()->json($cart[array_key_first($cart)]);
    }



    // public function

}
