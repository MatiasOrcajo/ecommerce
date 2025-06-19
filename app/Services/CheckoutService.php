<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Constants;
use App\Models\Coupon;
use App\Models\Product;
use App\Traits\CartTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use MercadoPago\MercadoPagoConfig;

class CheckoutService
{


    public function __construct(private OrderService         $orderService,
                                private MercadoPagoService   $mpService,
    )
    {
    }


    /**
     * Processes an order based on the provided request data, including payment and shipping methods.
     *
     * Decodes the incoming JSON data to extract the payment and shipping methods.
     * Creates and saves a new order using the provided data and selected methods.
     * Depending on the selected payment method, it either returns a JSON response with a success status and
     * order route, or initiates a payment process using an external service.
     *
     * @param Request $request The HTTP request containing order data in JSON format.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response A JSON response or the result of an external payment process.
     */
    public function processOrder(Request $request)
    {

        $paymentMethods = [
            "bank-transfer" => "Transferencia bancaria",
            "mercado-pago" => "Mercado Pago",
            "cash" => "Efectivo"
        ];

        $shippingMethods = [
            "correo-argentino" => "Correo Argentino",
            "andreani" => "Andreani",
            "take-away" => "Retiro en CABA"
        ];


        $request = json_decode($request->data);
        $selectedPaymentMethod = $request->payment_method;

        $order = $this->orderService->create($request);
        $order->shipping_method = $shippingMethods[$request->shipping_method];
        $order->payment_method = $paymentMethods[$selectedPaymentMethod];
        $order->save();
        Session::put("email_validated_$order->code", true);

        if ($selectedPaymentMethod == "bank-transfer" || $selectedPaymentMethod == "cash") {

            return response()->json([
                "success" => true,
                "route" => route('order-success', $order->code)
            ]);
        }

        if ($selectedPaymentMethod == "mercado-pago") {

            return $this->mpService->createPreference($order);

        }

    }


}
