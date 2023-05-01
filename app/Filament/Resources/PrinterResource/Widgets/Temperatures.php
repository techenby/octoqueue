<?php

namespace App\Filament\Resources\PrinterResource\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Temperatures extends Widget
{
    protected static string $view = 'filament.resources.printer-resource.widgets.temperatures';

    public ?Model $record = null;

    public $temperatures = [];

    protected int|string|array $columnSpan = 2;

    public function mount()
    {
        parent::mount();

        if ($this->record->status !== 'offline') {
            $this->loadTemperatures();
        }
    }

    public function clear($name, $type)
    {
        $this->temperatures[$name][$type] = 0;

        if ($type === 'target') {
            return $this->setTarget($name);
        }

        return $this->setOffSet($name);
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
            return Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }

        $offset = $this->temperatures[$name]['offset'];

        Notification::make()
            ->title($offset > 0 ? "Set {$name} offset to {$offset}" : 'Cleared offset')
            ->success()
            ->send();
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
            return Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }

        $temp = $this->temperatures[$name]['target'];

        Notification::make()
            ->title($temp > 0 ? "Set {$name} target temperature to {$temp}℃" : 'Cleared target temperature')
            ->success()
            ->send();
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
