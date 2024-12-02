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
        config(['app.asset_url' => 'https://reporting-api-dashboard-83eadac0eef4.herokuapp.com']);

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
