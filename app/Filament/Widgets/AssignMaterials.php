<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use App\Models\Printer;
use App\Models\Tool;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AssignMaterials extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getTableQuery(): Builder
    {
        $ids = Printer::forCurrentTeam()->select('id')->pluck('id');

        return Tool::whereIn('printer_id', $ids)
            ->whereNull('material_id');
    }

    protected function getTableColumns(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Stack::make([
                        TextColumn::make('printer.name')->weight('semibold'),
                        TextColumn::make('name')->color('secondary'),
                    ]),
                    SelectColumn::make('material_id')
                        ->label('Material')
                        ->options(Material::forCurrentTeam()->get()->pluck('name', 'id'))
                        ->columnSpan(2)
                        ->extraAttributes(['class' => 'w-full']),
                ]),
        ];
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-badge-check';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'All tools have materials.';
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
