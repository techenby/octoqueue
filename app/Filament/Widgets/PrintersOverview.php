<?php

namespace App\Filament\Widgets;

use App\Jobs\FetchPrinterStatus;
use App\Models\Material;
use App\Models\Printer;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PrintersOverview extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getTableQuery(): Builder
    {
        return Printer::forCurrentTeam();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->weight('semibold'),
            TextColumn::make('model')->color('secondary'),
            BadgeColumn::make('status')
                ->colors([
                    'secondary' => 'draft',
                    'warning' => 'closed',
                    'success' => static fn ($state): bool => $state === 'operational' || $state === 'printing',
                    'danger' => static fn ($state): bool => $state === 'offline' || $state === 'error',
                ]),
            SelectColumn::make('material_id')
                ->label('Material')
                ->options(Material::forCurrentTeam()->get()->pluck('name', 'id'))
                ->extraAttributes(['class' => 'w-40']),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('camera')
                ->action(fn (Printer $record) => $this->emit('pip', $record->id)),
            Action::make('fetchStatus')
                ->action(fn (Printer $record) => FetchPrinterStatus::dispatch($record)),
            Action::make('edit')
                ->url(fn (Printer $record): string => route('filament.resources.printers.view', $record)),
        ];
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-badge-check';
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
