<?php

namespace App\Jobs;

use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOrderSentEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $orderId)
    {
        //
    }


    private function generateOrderCode()
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = 'GIFT-';

        for ($i = 4; $i < 13; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }


        return $code;
    }

    /**
     * Execute the job.
     */
    public function handle(Order $order): void
    {

        $order = Order::with(['customer', 'products.productVariant.pictures'])->find($this->orderId);
        $code = $this->generateOrderCode();

        $random = random_int(0, 2);
        $coupon = new Coupon();
        $coupon->code = $code;
        $coupon->quantity = 1;
        $coupon->valid_until = Carbon::now()->addDays(30);
        $possibleDiscounts = [10, 15, 20];
        $coupon->discount = $possibleDiscounts[$random];
        $coupon->save();

        Mail::send('emails.order-sent', ['order' => $order, 'coupon' => $coupon], function ($message) use ($order) {
            $message->to($order->customer->email)
                ->subject('Enviamos tu pedido!');
        });
    }
}
