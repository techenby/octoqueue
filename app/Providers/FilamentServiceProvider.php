<?php

namespace App\Providers;

use pxlrbt\FilamentEnvironmentIndicator\FilamentEnvironmentIndicator;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        FilamentEnvironmentIndicator::configureUsing(function ($indicator) {
            $indicator->visible = fn () => auth()->user()?->hasRole('Super-Admin');
        }, isImportant: true);
    }
}
