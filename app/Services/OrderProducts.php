<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class OrderProducts{


    public function __construct()
    {
    }

    public function create(Order $order, $product)
    {
        dd($product);
        $cart = Session::get('cart');

        foreach ($cart as $p){
            $orderProduct               = new \App\Models\OrderProducts();
            $orderProduct->product_id   = $p["id"];
            $orderProduct->order_id     = $order->id;
            $orderProduct->quantity     = $p["quantity"];
            $orderProduct->unit_price   = $p["price"];
            $orderProduct->subtotal     = $p["quantity"] * $p["price"];

            $orderProduct->save();

        }

    }

}
