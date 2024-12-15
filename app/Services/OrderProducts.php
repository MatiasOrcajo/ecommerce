<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Session;

class OrderProducts{

    public function create(Order $order)
    {
        $cart = Session::get('cart');

        foreach ($cart as $p){
            dd($p);
        }

    }

}
