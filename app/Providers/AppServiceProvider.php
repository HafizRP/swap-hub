<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS only if accessing via domain
        if (request()->getHost() === 'swaphub.b14.my.id') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
