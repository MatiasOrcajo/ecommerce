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

//    public function __construct(private readonly CheckoutService $checkoutService)
//    {
//
//    }


    public function index()
    {
        return view('checkout');
    }


    /**
     * @param Request $request
     */
    public function pay(Request $request)
    {

        return $this->checkoutService->processOrder($request);

//        return $this->mpService->createPreference($request);
    }



    /**
     * Handles the processing of a successful order.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function processOrderSuccess($code)
    {
        $order = Order::where('code', $code)->first();

        return view('checkout.order-success', compact('order'));
    }


    /**
     * @param Request $request
     * @return void
     */
    public function success(Request $request, Order $order)
    {
        $order->status = 'Pago recibido';
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
