<?php

namespace App\Http\Livewire\Jobs;

use App\Models\Job;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public Job $job;

    public $name;
    public $print_type_id;
    public $color_hex;
    public $files;
    public $notes;

    public function mount(): void
    {
        if (isset($this->job)) {
            $this->form->fill([
                'name' => $this->job->name,
                'print_type_id' => $this->job->print_type_id,
                'color_hex' => $this->job->color_hex,
                'files' => $this->job->files,
                'notes' => $this->job->notes,
            ]);
        }
    }

    protected function getFormModel(): string
    {
        return isset($this->job) ? $this->job : Job::class;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required(),
            Select::make('print_type_id')
                ->relationship('printType', 'name')
                ->label('Print Type')
                ->required(),
            Select::make('color_hex')
                ->options($this->colorOptions)
                ->label('Material Color'),
            Repeater::make('files')
                ->schema([
                    Select::make('printer')
                        ->options($this->printers->pluck('name', 'id'))
                        ->required(),
                    TextInput::make('file')->required(),
                ])
                ->cloneable()
                ->collapsible()
                ->createItemButtonLabel('Add file from printer')
                ->maxItems($this->printers->count()),
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
        return view('livewire.filament.form', [
            'title' => 'Create Print Job',
            'breadcrumbs' => [
                ['label' => 'Queue', 'route' => 'queue'],
                ['label' => 'Create Print Job'],
            ],
        ]);
    }

    public function getColorOptionsProperty()
    {
        return auth()->user()->currentTeam->materials->pluck('color', 'color_hex');
    }

    public function getPrintersProperty()
    {
        return auth()->user()->currentTeam->printers;
    }

    public function submit(): void
    {
        if (isset($this->job)) {
            $this->job->update($this->form->getState());
            $message = 'Changes to the **job** have been saved.';
        } else {
            auth()->user()->currentTeam->jobs()->create(
                array_merge($this->form->getState(), ['user_id' => auth()->id()]),
            );
            $message = 'The **job** has been created.';
        }

        Notification::make()
            ->title('Saved successfully')
            ->body($message)
            ->success()
            ->duration(5000)
            ->send();
    }
}
