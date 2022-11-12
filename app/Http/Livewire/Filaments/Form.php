<?php

namespace App\Http\Livewire\Filaments;

use App\Calculator;
use App\Models\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public Filament $filament;

    public $color;
    public $color_hex;
    public $brand;
    public $cost;
    public $material;
    public $diameter;
    public $empty;

    public function mount(): void
    {
        if (isset($this->filament)) {
            $this->form->fill([
                'color' => $this->filament->color,
                'color_hex' => $this->filament->color_hex,
                'brand' => $this->filament->brand,
                'cost' => $this->filament->cost,
                'material' => $this->filament->material,
                'diameter' => $this->filament->diameter,
                'empty' => $this->filament->empty,
            ]);
        }
    }

    protected function getFormModel(): string
    {
        return isset($this->filament) ? $this->filament : Filament::class;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('color')->required(),
            ColorPicker::make('color_hex')->required(),
            TextInput::make('brand')->required(),
            TextInput::make('cost')
                ->mask(fn (Mask $mask) => $mask->money(prefix: '$', thousandsSeparator: ',', decimalPlaces: 2, isSigned: false))
                ->required(),
            TextInput::make('material')
                ->datalist(array_keys((new Calculator)->materials))
                ->required(),
            TextInput::make('diameter')->required(),
            TextInput::make('empty')->label('Empty Spool Weight')->required(),
        ];
    }

    public function render(): View
    {
        return view('livewire.printers.form');
    }

    public function submit(): void
    {
        if (isset($this->filament)) {
            $this->filament->update($this->form->getState());
        } else {
            auth()->user()->currentTeam()->filaments()->create($this->form->getState());
        }
    }
}
