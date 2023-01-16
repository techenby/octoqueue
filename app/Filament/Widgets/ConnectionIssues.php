<?php

namespace App\Filament\Widgets;

use App\Models\Printer;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ConnectionIssues extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return Printer::forCurrentTeam()->whereIn('status', ['offline', 'error']);
    }

    protected function getTableColumns(): array
    {
        return [
            Stack::make([
                TextColumn::make('name')->weight('semibold'),
                TextColumn::make('model')->color('secondary'),
            ])
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->url(fn (Printer $record): string => route('filament.resources.printers.edit', $record))
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
