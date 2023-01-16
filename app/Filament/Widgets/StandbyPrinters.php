<?php

namespace App\Filament\Widgets;

use App\Jobs\FetchPrinterStatus;
use App\Models\Printer;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class StandbyPrinters extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getTableQuery(): Builder
    {
        return Printer::forCurrentTeam()->whereIn('status', ['operational']);
    }

    protected function getTableColumns(): array
    {
        return [
            Stack::make([
                TextColumn::make('name')->weight('semibold'),
                TextColumn::make('model')->color('secondary'),
            ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->action(fn (Printer $record) => $this->emit('pip', $record->id)),
            Action::make('fetchStatus')
                ->action(fn (Printer $record) => FetchPrinterStatus::dispatch($record)),
            Action::make('edit')
                ->url(fn (Printer $record): string => route('filament.resources.printers.edit', $record)),
        ];
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-badge-check';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No printers are on standby.';
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
