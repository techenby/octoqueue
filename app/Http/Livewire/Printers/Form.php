<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
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

    protected function getFormModel(): Printer | string
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
        return view('livewire.filament.form', [
            'title' => isset($this->printer) ? "Edit {$this->printer->name}" : 'Create Printer',
            'breadcrumbs' => [
                ['label' => 'Printers', 'route' => 'printers'],
                ['label' => isset($this->printer) ? "Edit {$this->printer->name}" : 'Create Printer'],
            ],
        ]);
    }

    public function submit()
    {
        if (isset($this->printer)) {
            $this->printer->update($this->form->getState());
            $message = 'Changes to the **printer** have been saved.';
        } else {
            auth()->user()->currentTeam->printers()->create($this->form->getState());
            $message = 'The **printer** has been created.';
        }

        Notification::make()
            ->title('Saved successfully')
            ->body($message)
            ->success()
            ->duration(5000)
            ->send();

        return $this->redirect(route('printers'));
    }
}
