<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Tool extends Component implements HasForms
{
    use InteractsWithForms;

    public Printer $printer;

    public $amount = 5;

    public $sign = '+';

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('amount')
                ->disabled(in_array($this->printer->status, ['printing', 'error', 'closed', 'offline']))
                ->minValue(0)
                ->numeric()
                ->suffix('mm'),
            Radio::make('sign')
                ->options([
                    '+' => 'Extrude',
                    '-' => 'Retract',
                ]),
        ];
    }

    public function render()
    {
        return view('livewire.printers.tool');
    }

    public function submit()
    {
        $amount = $this->sign == '+' ? $this->amount : '-'.$this->amount;

        $response = Http::octoPrint($this->printer)
            ->post('api/printer/tool', [
                'command' => 'extrude',
                'amount' => (int) $amount,
            ]);

        if ($response->failed()) {
            return Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }

        Notification::make()
            ->title('Sent command to printer')
            ->body(($this->sign == '+' ? 'Extruding ' : 'Retracting ').$this->amount.'mm')
            ->success()
            ->send();
    }
}
