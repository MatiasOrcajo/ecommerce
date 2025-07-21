<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(LaravelLogViewerServiceProvider::class);

        }

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
