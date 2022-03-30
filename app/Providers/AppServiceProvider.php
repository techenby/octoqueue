<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Component;

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

    public function boot()
    {
        Component::macro('notify', function ($type, $message) {
            $this->emit('notify', ['type' => $type, 'message' => $message]);
        });
    }
}
