<?php

namespace App\Filament\Resources\PrinterResource\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class AxisControls extends Widget
{
    protected static string $view = 'filament.resources.printer-resource.widgets.axis-controls';
    public ?Model $record = null;

    public $amount = 10;

    public function home($axis)
    {
        $response = Http::octoPrint($this->record)->post('api/printer/printhead', [
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

    private function jog($x, $y, $z)
    {
        $response = Http::octoPrint($this->record)
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
