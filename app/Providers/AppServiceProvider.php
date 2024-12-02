<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        config(['app.asset_url' => 'https://reporting-dashboard-api-cgid.onrender.com']);

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
