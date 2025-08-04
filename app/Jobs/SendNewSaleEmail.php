<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendNewSaleEmail implements ShouldQueue
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
        $order = Order::with(['customer', 'products.productVariant.pictures'])->find($this->orderId);

        Mail::send('emails.new-sale', ['order' => $order], function ($message) use ($order) {
            $message->to(["sofia@atica.com.ar", "matias@atica.com.ar"])
                ->subject('Nueva venta!');
        });
    }
}
