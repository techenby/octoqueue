<?php

namespace App\Http\Livewire\Printers;

use App\Jobs\FetchPrinterStatus;
use App\Models\Printer;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component implements HasForms
{
    use InteractsWithForms;

    public Printer $printer;

    public $amount = 10;

    public $autoconnect;

    public $baudrate;

    public $connectionOptions;

    public $extrudeAmount = 5;

    public $port;

    public $printerProfile;

    public $save;

    public $sign = '+';

    public $temps;

    public function mount()
    {
        $this->loadConnection();
        $this->loadTemps();
    }

    protected function getConnectionFormSchema(): array
    {
        return [
            Select::make('port')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->options(array_combine($this->connectionOptions['ports'], $this->connectionOptions['ports']))
                ->placeholder('AUTO'),
            Select::make('baudrate')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->options(array_combine($this->connectionOptions['baudrates'], $this->connectionOptions['baudrates']))
                ->placeholder('AUTO'),
            Select::make('printerProfile')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->options(collect($this->connectionOptions['printerProfiles'])->pluck('name', 'id'))
                ->required(),
            Checkbox::make('save')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->label('Save connection settings'),
            Checkbox::make('autoconnect')
                ->disabled(in_array($this->printer->status, ['operational', 'printing']))
                ->label('Auto-connect on startup'),
        ];
    }

    protected function getToolFormSchema(): array
    {
        return [
            TextInput::make('extrudeAmount')
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

    protected function getForms(): array
    {
        return [
            'connectionForm' => $this->makeForm()
                ->schema($this->getConnectionFormSchema()),
            'toolForm' => $this->makeForm()
                ->schema($this->getToolFormSchema()),
        ];
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

    public function connect()
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

    public function deletePrinter()
    {
        $this->printer->safeDelete();

        return redirect('printers');
    }

    public function extrude()
    {
        $amount = $this->sign == '+' ? $this->extrudeAmount : '-' . $this->extrudeAmount;

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
            ->body(($this->sign == '+' ? 'Extruding ' : 'Retracting ') . $this->extrudeAmount . 'mm')
            ->success()
            ->send();
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

    private function loadConnection()
    {
        $response = Http::octoPrint($this->printer)->get('/api/connection')->json();

        $this->connectionOptions = $response['options'];

        $this->connectionForm->fill([
            'baudrate' => $response['current']['baudrate'] ?? $response['options']['baudratePreference'],
            'port' => $response['current']['port'] ?? $response['options']['portPreference'],
            'printerProfile' => $response['current']['printerProfile'] ?? $response['options']['printerProfilePreference'],
            'autoconnect' => $this->connectOptions['autoconnect'] ?? false,
        ]);
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
