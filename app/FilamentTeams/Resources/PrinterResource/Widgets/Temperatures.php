<?php

namespace App\FilamentTeams\Resources\PrinterResource\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Temperatures extends Widget
{
    public ?Model $record = null;
    public $temperatures;

    protected int|string|array $columnSpan = 2;

    protected static string $view = 'filament.resources.printer-resource.widgets.temperatures';

    public function mount()
    {
        parent::mount();

        $this->loadTemperatures();
    }

    public function setOffset($name)
    {
        if ($name === 'bed') {
            $response = Http::octoPrint($this->record)->post('api/printer/bed', [
                'command' => 'offset',
                'offset' => (int) $this->temperatures[$name]['offset'],
            ]);
        } else {
            $response = Http::octoPrint($this->record)->post('api/printer/tool', [
                'command' => 'offset',
                'offsets' => [
                    $name => (int) $this->temperatures[$name]['offset'],
                ],
            ]);
        }

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }
    }

    public function setTarget($name)
    {
        if ($name === 'bed') {
            $response = Http::octoPrint($this->record)->post('api/printer/bed', [
                'command' => 'target',
                'target' => (int) $this->temperatures[$name]['target'],
            ]);
        } else {
            $response = Http::octoPrint($this->record)->post('api/printer/tool', [
                'command' => 'target',
                'targets' => [
                    $name => (int) $this->temperatures[$name]['target'],
                ],
            ]);
        }

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }
    }

    private function loadTemperatures()
    {
        $response = Http::octoPrint($this->record)->get('api/printer');

        if ($response->failed() && $response->json('error') !== 'Printer is not operational') {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }

        if ($response->json('error') && $this->record->status !== 'error') {
            $this->record->updateQuietly([
                'status' => 'error', // or offline?
            ]);
        }

        if (isset($response->json()['temperature'])) {
            $this->temperatures = array_filter($response->json()['temperature'], fn ($temp, $key) => Str::startsWith($key, 'tool') || $key == 'bed', ARRAY_FILTER_USE_BOTH);
        } else {
            $this->temperatures = [];
        }
    }
}
