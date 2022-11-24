<?php

namespace App\Http\Livewire\Materials;

use App\Models\Material;
use Closure;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;

class Table extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return Material::query()->whereTeamId(auth()->user()->current_team_id);
    }

    protected function getTableColumns(): array
    {
        return [
            ColorColumn::make('color_hex')
                ->label('Color')
                ->tooltip(fn (Material $record): string => $record->color)
                ->sortable(),
            TextColumn::make('brand')
                ->sortable(),
            TextColumn::make('cost')
                ->formatStateUsing(fn (string $state) => "$$state")
                ->sortable(),
            TextColumn::make('type')
                ->sortable(),
            TextColumn::make('diameter')
                ->formatStateUsing(fn (string $state) => "{$state}mm")
                ->sortable(),
            TextColumn::make('formatted_current_weight')
                ->label('Current Weight'),
            TextColumn::make('formatted_current_length')
                ->label('Current Length'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            //
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ReplicateAction::make()
                ->excludeAttributes(['weights']),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('delete')
                ->label('Delete selected')
                ->color('danger')
                ->action(function (Collection $records): void {
                    $records->each->delete();
                })
                ->requiresConfirmation(),
        ];
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Material $material): string => route('materials.edit', ['material' => $material]);
    }

    public function render(): View
    {
        return view('livewire.filament.table', [
            'title' => 'Materials',
            'link' => ['route' => 'materials.create', 'label' => 'Create'],
            'breadcrumbs' => [
                ['label' => 'Materials']
            ],
        ]);
    }
}
