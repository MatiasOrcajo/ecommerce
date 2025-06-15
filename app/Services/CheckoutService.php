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


    public function __construct(private OrderService $orderService,
                                private OrderProductsService $orderProductsService
    )
    {
    }

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

        if($selectedPaymentMethod == "bank-transfer" || $selectedPaymentMethod == "cash"){

            return response()->json([
               "success" => true,
               "route" => route('order-success', $order->code)
            ]);
        }

    }


}
