<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class Temps extends Component
{
    public Printer $printer;

    public $temps;

    public function mount()
    {
        $this->loadTemps();
    }

    public function render()
    {
        return view('livewire.printers.temps');
    }

    public function clear($name, $type)
    {
        $this->temps[$name][$type] = 0;

        if ($type === 'target') {
            return $this->setTarget($name);
        }

        return $this->setOffSet($name);
    }

    public function loadTemps()
    {
        $response = Http::octoPrint($this->printer)->get('api/printer');

        if ($response->failed()) {
            return Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }

        $this->temps = array_filter($response->json()['temperature'], fn ($temp, $key) => Str::startsWith($key, 'tool') || $key == 'bed', ARRAY_FILTER_USE_BOTH);
    }

    public function setOffset($name)
    {
        if ($name === 'bed') {
            $response = Http::octoPrint($this->printer)->post('api/printer/bed', [
                'command' => 'offset',
                'offset' => (int) $this->temps[$name]['offset'],
            ]);
        } else {
            $response = Http::octoPrint($this->printer)->post('api/printer/tool', [
                'command' => 'offset',
                'offsets' => [
                    $name => (int) $this->temps[$name]['offset'],
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
            $response = Http::octoPrint($this->printer)->post('api/printer/bed', [
                'command' => 'target',
                'target' => (int) $this->temps[$name]['target'],
            ]);
        } else {
            $response = Http::octoPrint($this->printer)->post('api/printer/tool', [
                'command' => 'target',
                'targets' => [
                    $name => (int) $this->temps[$name]['target'],
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
}
