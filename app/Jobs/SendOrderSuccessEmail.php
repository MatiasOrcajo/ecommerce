<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderSuccessEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $orderId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(Order $order): void
    {
        Log::info(config('mail.default'));

        $order = Order::with(['customer', 'products.productVariant.pictures'])->find($this->orderId);

        Mail::send('emails.order-success', ['order' => $order], function ($message) use ($order) {
            $message->to($order->customer->email)
                ->subject('Gracias por tu compra!');
        });
    }
}
