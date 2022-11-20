<?php

namespace App\Http\Livewire\Materials;

use Facades\App\Calculator;
use App\Models\Material;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public Material $material;

    public $printer_type;
    public $color;
    public $color_hex;
    public $brand;
    public $cost;
    public $type;
    public $diameter;
    public $empty;

    public function mount(): void
    {
        if (isset($this->material)) {
            $this->form->fill([
                'printer_type' => $this->material->printer_type,
                'color' => $this->material->color,
                'color_hex' => $this->material->color_hex,
                'brand' => $this->material->brand,
                'cost' => $this->material->cost,
                'type' => $this->material->type,
                'diameter' => $this->material->diameter,
                'empty' => $this->material->empty,
            ]);
        }
    }

    protected function getFormModel(): Material | string
    {
        return isset($this->material) ? $this->material : Material::class;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('brand')
                ->required(),
            TextInput::make('cost')
                ->mask(fn (Mask $mask) => $mask->money(prefix: '$', thousandsSeparator: ',', decimalPlaces: 2, isSigned: false))
                ->required(),
            TextInput::make('color')
                ->required(),
            ColorPicker::make('color_hex')
                ->label('Color HEX')
                ->required(),
            Radio::make('printer_type')
                ->label('Printer Type')
                ->options([
                    'fdm' => 'FDM',
                    'sla' => 'SLA',
                ])
                ->lazy(),
            TextInput::make('type')
                ->datalist(fn ($get) => Calculator::materialByType($get('printer_type')))
                ->hidden(fn ($get) => $get('printer_type') === null)
                ->required(),
            TextInput::make('diameter')
                ->hidden(fn ($get) => $get('printer_type') === null || $get('printer_type') === 'sla'),
            TextInput::make('empty')
                ->hidden(fn ($get) => $get('printer_type') === null)
                ->label(fn ($get) => $get('printer_type') === 'fdm' ? 'Empty Spool Weight' : 'Empty Bottle Weight'),
        ];
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }

    public function render(): View
    {
        $title = isset($this->material) ? "Edit {$this->material->name}" : 'Create Material';

        return view('livewire.filament.form', [
            'title' => $title,
            'breadcrumbs' => [
                ['label' => 'Materials', 'route' => 'materials'],
                ['label' => $title],
            ],
        ]);
    }

    public function submit(): void
    {
        if (isset($this->material)) {
            $this->material->update($this->form->getState());
            $message = 'Changes to the **material** have been saved.';
        } else {
            auth()->user()->currentTeam->materials()->create($this->form->getState());
            $message = 'The **material** has been created.';
        }

        Notification::make()
            ->title('Saved successfully')
            ->body($message)
            ->success()
            ->duration(5000)
            ->send();
    }
}
