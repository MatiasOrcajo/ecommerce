<?php

use App\Jobs\LiberateStockFromExpiredOrdersJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Schedule::job(new LiberateStockFromExpiredOrdersJob())
    ->dailyAt('05:00')
    ->withoutOverlapping()
    ->onOneServer();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
