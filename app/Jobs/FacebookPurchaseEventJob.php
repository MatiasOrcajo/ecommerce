<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Product;
use App\Services\FacebookAdsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FacebookPurchaseEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;   // se serializa por id gracias a SerializesModels
    public array $ctx;         // datos planos del request

    public function __construct(Order $order, array $ctx)
    {
        $this->order = $order;
        $this->ctx     = $ctx;
    }

    public function handle(FacebookAdsService $facebook): void
    {
        $facebook->purchaseEvent($this->order, $this->ctx);
    }
}
