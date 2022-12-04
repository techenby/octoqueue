<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Control extends Component
{
    public Printer $printer;

    public $amount = 10;

    public function render()
    {
        return view('livewire.printers.control');
    }

    public function move($axis, $direction = '')
    {
        $value = $direction.$this->amount;

        if ($axis === 'x') {
            $this->jog((float) $value, 0, 0);
        } elseif ($axis === 'y') {
            $this->jog(0, (float) $value, 0);
        } elseif ($axis === 'z') {
            $this->jog(0, 0, (float) $value);
        }
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
}
