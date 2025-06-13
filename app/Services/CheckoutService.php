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

        $paymentMethods = [];

        $order = $this->orderService->create(json_decode($request->data));
        $order->status = "Orden no paga por el cliente";
        $order->save();

        // Retrieves items to be purchased, with final price including discounts
        $items = $this->orderProductsService->mapOrderProductToItem($order->id);
    }
}
