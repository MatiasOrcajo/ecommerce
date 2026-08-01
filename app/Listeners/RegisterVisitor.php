<?php

namespace App\Listeners;

use App\Events\NewVisitor;
use App\Models\Visitor;

class RegisterVisitor
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(NewVisitor $event): void
    {
        // Verifica si la IP ya se registró hoy
        if (! Visitor::where('ip_address', $event->ipAddress)->whereDate('created_at', today())->exists()) {
            Visitor::create(['ip_address' => $event->ipAddress]);
        }
    }
}
