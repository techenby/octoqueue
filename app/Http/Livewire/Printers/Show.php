<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    public Printer $printer;

    public $amount = 10;

    public $temps;

    public function mount()
    {
        $this->loadTemps();
    }

    public function render()
    {
        return view('livewire.printers.show', [
            'title' => $this->printer->name,
            'breadcrumbs' => [
                ['label' => 'Printers', 'route' => 'printers'],
                ['label' => $this->printer->name],
            ],
            'tools' => $this->printer->tools,
        ]);
    }

    public function clear($name, $type)
    {
        $this->temps[$name][$type] = 0;

        if ($type === 'target') {
            return $this->setTarget($name);
        }

        return $this->setOffSet($name);
    }

    public function deletePrinter()
    {
        $this->printer->safeDelete();

        return redirect('printers');
    }

    public function home($axis)
    {
        $response = Http::octoPrint($this->printer)->post('api/printer/printhead', [
            'command' => 'home',
            'axes' => $axis,
        ]);

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }
    }

    public function move($axis, $direction = '')
    {
        $value = $direction . $this->amount;

        if ($axis === 'x') {
            $this->jog((float) $value, 0, 0);
        } elseif ($axis === 'y') {
            $this->jog(0, (float) $value, 0);
        } elseif ($axis === 'z') {
            $this->jog(0, 0, (float) $value);
        }
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

    private function jog($x, $y, $z)
    {
        $response = Http::octoPrint($this->printer)
            ->post('api/printer/printhead', [
                'command' => 'jog',
                'x' => $x,
                'y' => $y,
                'z' => $z,
            ]);

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }
    }

    private function loadTemps()
    {
        $response = Http::octoPrint($this->printer)->get('api/printer');

        if ($response->failed() && $response->json('error') !== 'Printer is not operational') {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }

        if ($response->json('error') && $this->printer->status !== 'error') {
            $this->printer->updateQuietly([
                'status' => 'error', // or offline?
            ]);
        }

        if (isset($response->json()['temperature'])) {
            $this->temps = array_filter($response->json()['temperature'], fn ($temp, $key) => Str::startsWith($key, 'tool') || $key == 'bed', ARRAY_FILTER_USE_BOTH);
        } else {
            $this->temps = [];
        }
    }
}
