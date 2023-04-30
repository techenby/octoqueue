<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use pxlrbt\FilamentEnvironmentIndicator\FilamentEnvironmentIndicator;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()
                    ->label('Profile')
                    ->url(route('filament.pages.profile'))
                    ->icon('heroicon-s-cog'),
            ]);
        });

        Filament::registerRenderHook(
            'user-menu.start',
            fn (): View => view('layouts.teams-dropdown'),
        );

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
