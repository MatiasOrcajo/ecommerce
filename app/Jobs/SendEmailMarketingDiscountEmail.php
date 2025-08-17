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
    public function __construct(public $customer, public $coupon)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        Mail::send('emails.email-mkt-first', ['customer' => $this->customer, 'coupon' => $this->coupon], function ($message)  {
            $message->to($this->customer->email)
                ->subject('Gracias por suscribirte!');
        });
    }
}
