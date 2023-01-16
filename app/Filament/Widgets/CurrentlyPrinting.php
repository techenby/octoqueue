<?php

namespace App\Filament\Widgets;

use App\Models\Printer;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class CurrentlyPrinting extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Printer::forCurrentTeam()->whereIn('status', ['printing', 'paused']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Printer'),
            ViewColumn::make('materials')
                ->view('filament.tables.columns.materials'),
            ViewColumn::make('progress')
                ->view('filament.tables.columns.progress'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('save')
                ->action(function (Printer $record) {
                    $record->saveCurrentlyPrinting();
                })
                ->hidden(fn (Printer $record) => $record->currentJob->isNotEmpty())
                ->tooltip('Save what is currently printing to jobs. This allows for better material usage tracking.'),
            Action::make('done')
                ->action(function (Printer $record) {
                    $record->currentJob->markAsComplete();
                })
                ->hidden(fn (Printer $record) => $record->currentJob->isEmpty())
                ->tooltip('Mark the this job as complete.'),
            Action::make('pause')
                ->action(function (Printer $record) {
                    if ($record->status === 'printing') {
                        $record->pause();
                    }
                })
                ->hidden(fn (Printer $record) => $record->status !== 'printing'),
            Action::make('resume')
                ->action(function (Printer $record) {
                    if (in_array($record->status, ['paused', 'pausing'])) {
                        $record->resume();
                    }
                })
                ->hidden(fn (Printer $record) => $record->status === 'printing'),
            Action::make('stop')
                ->action(function (Printer $record) {
                    if (in_array($record->status, ['printing', 'printing'])) {
                        $record->cancel();
                    }
                })
                ->tooltip('Stop printing and mark the current job as failed.'),
            Action::make('watch')
                ->action(function (Printer $record) {

                    $this->emit('pip', $record->id);
                }),
        ];
    }
}
