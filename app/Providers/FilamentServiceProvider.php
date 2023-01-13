<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use pxlrbt\FilamentEnvironmentIndicator\FilamentEnvironmentIndicator;

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

        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');
        });

        Filament::registerScripts([
            'https://cdn.jsdelivr.net/npm/@marcreichel/alpine-timeago@latest/dist/alpine-timeago.min.js',
        ], true);
    }
}
