<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Http::macro('octoPrint', function ($printer) {
            return Http::withHeaders([
                'X-Api-Key' => $printer->api_key,
            ])->baseUrl($printer->url);
        });
    }
}
