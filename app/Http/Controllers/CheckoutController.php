<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\MercadoPagoService;
use App\Traits\CartTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use MercadoPago\Client\Preference\PreferenceClient;

class CheckoutController extends Controller
{

    use CartTrait;

    public function __construct(private readonly MercadoPagoService $mpService,
                                private readonly CheckoutService $checkoutService,
                                private readonly CartService $cartService)
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
     * @param Order $order
     */
    public function success(Request $request, $encrypted)
    {
        $orderId = Crypt::decryptString($encrypted);
        $order = Order::find($orderId);
        $order->status = 'Pago recibido';
        $order->save();

        Session::put("email_validated_$order->code", true);

        $this->cartService->clearCart();

        return redirect()->route('order-success', $order->code);

    }


    /**
     * @param Request $request
     * @return void
     */
    public function failure(Request $request, $encrypted)
    {
        $orderId = Crypt::decryptString($encrypted);
        $order = Order::find($orderId);
        $order->status = 'Pago rechazado';
        $order->save();

    }



    /**
     * @param Request $request
     * @return void
     */
    public function pending(Request $request, $encrypted)
    {
        $orderId = Crypt::decryptString($encrypted);
        $order = Order::find($orderId);
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

        return response()->json($this->checkoutService->getCartInfo());
    }



    // public function

}
