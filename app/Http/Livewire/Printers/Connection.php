<?php

namespace App\Http\Livewire\Printers;

use App\Jobs\FetchPrinterStatus;
use App\Models\Printer;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Connection extends Component implements HasForms
{
    use InteractsWithForms;

    public Printer $printer;

    public $options;

    public $baudrate;
    public $port;
    public $printerProfile;
    public $save;
    public $autoconnect;

    public function mount()
    {
        $this->loadConnection();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('port')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->options(array_combine($this->options['ports'], $this->options['ports']))
                ->placeholder('AUTO'),
            Select::make('baudrate')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->options(array_combine($this->options['baudrates'], $this->options['baudrates']))
                ->placeholder('AUTO'),
            Select::make('printerProfile')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->options(collect($this->options['printerProfiles'])->pluck('name', 'id'))
                ->required(),
            Checkbox::make('save')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->label('Save connection settings'),
            Checkbox::make('autoconnect')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->label('Auto-connect on startup'),
        ];
    }

    public function render()
    {
        return view('livewire.printers.connection');
    }

    public function disconnect()
    {
        $response = Http::octoPrint($this->printer)->post('api/connection', ['command' => 'disconnect']);

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        } else {
            FetchPrinterStatus::dispatch($this->printer);

            $this->loadConnection();
        }
    }

    public function submit()
    {
        $response = Http::octoPrint($this->printer)
            ->post('api/connection', array_filter(array_merge($this->form->getState(), [
                'command' => 'connect',
            ])));

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        } else {
            FetchPrinterStatus::dispatch($this->printer);

            $this->loadConnection();
        }
    }

    private function loadConnection()
    {
        $response = Http::octoPrint($this->printer)->get('/api/connection')->json();

        $this->options = $response['options'];

        $this->form->fill([
            'baudrate' => $response['current']['baudrate'] ?? $response['options']['baudratePreference'],
            'port' => $response['current']['port'] ?? $response['options']['portPreference'],
            'printerProfile' => $response['current']['printerProfile'] ?? $response['options']['printerProfilePreference'],
            'autoconnect' => $this->options['autoconnect'] ?? false,
        ]);
    }
}
