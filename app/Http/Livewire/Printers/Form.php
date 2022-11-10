<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public Printer $printer;

    public $name;
    public $model;
    public $url;
    public $api_key;

    public function mount(): void
    {
        if (isset($this->printer)) {
            $this->form->fill([
                'name' => $this->printer->name,
                'model' => $this->printer->model,
                'url' => $this->printer->url,
                'api_key' => $this->printer->api_key,
            ]);
        }
    }

    protected function getFormModel(): string
    {
        return isset($this->printer) ? $this->printer : Printer::class;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')->required(),
            TextInput::make('model'),
            TextInput::make('url')->url()->label('URL'),
            TextInput::make('api_key')->password()->label('API Key'),
        ];
    }

    public function render(): View
    {
        return view('livewire.printers.form');
    }

    public function submit(): void
    {
        if (isset($this->printer)) {
            $this->printer->update($this->form->getState());
        } else {
            Printer::create(array_merge(
                ['team_id' => auth()->user()->current_team_id],
                $this->form->getState()
            ));
        }
    }
}
