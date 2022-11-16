<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
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
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('Frontend')
                    ->url(route('dashboard'))
                    ->icon('heroicon-s-cog'),
                UserMenuItem::make()
                    ->label('Marketing')
                    ->url(route('marketing'))
                    ->icon('heroicon-s-cog'),
            ]);
        });
    }
}
