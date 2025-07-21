<?php

namespace App\Services;

use App\Jobs\SendOrderSuccessEmail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendOrderSuccess(Order $order)
    {

        SendOrderSuccessEmail::dispatch($order->id)->delay(now()->addSeconds(5));

    }
}
