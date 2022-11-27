<?php

namespace App\Http\Livewire\Materials;

use App\Models\Material;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
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
                ->searchable(['color', 'color_hex'])
                ->sortable()
                ->toggleable(),
            TextColumn::make('brand')
                ->searchable()
                ->sortable()
                ->toggleable(),
            TextColumn::make('cost')
                ->formatStateUsing(fn (string $state) => "$$state")
                ->searchable()
                ->sortable()
                ->toggleable(),
            TextColumn::make('type')
                ->searchable()
                ->sortable()
                ->toggleable(),
            TextColumn::make('diameter')
                ->formatStateUsing(fn (string $state) => "{$state}mm")
                ->searchable()
                ->sortable()
                ->toggleable(),
            TextColumn::make('formatted_current_weight')
                ->label('Current Weight')
                ->toggleable()
                ->action(
                    Action::make('add_current_weight')
                        ->action(function (Material $record, $data): void {
                            $record->addWeight($data['current_weight']);
                        })
                        ->form([
                            TextInput::make('current_weight')
                                ->helperText('Put the spool or bottle on a scale and record the amount here. Make no adjustment for the weight of the container.')
                                ->label('Current Weight')
                                ->numeric()
                                ->required()
                                ->suffix('g'),
                        ]),
                ),
            TextColumn::make('formatted_current_length')
                ->label('Current Length')
                ->toggleable(),
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
                ['label' => 'Materials'],
            ],
        ]);
    }
}
