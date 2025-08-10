<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEmailMarketingDiscountEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

//        Mail::send('emails.email-mkt-first', ['customer' => $customer, 'coupon' => $coupon], function ($message) use ($order) {
//            $message->to(["sofia@atica.com.ar", "matias@atica.com.ar"])
//                ->subject('Nueva venta!');
//        });
    }
}
