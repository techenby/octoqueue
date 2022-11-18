<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Closure;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
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
        return Printer::query()->whereTeamId(auth()->user()->current_team_id);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name'),
            TextColumn::make('model'),
            BadgeColumn::make('status')
                ->colors([
                    'secondary' => 'draft',
                    'warning' => 'closed',
                    'success' => static fn ($state): bool => $state === 'operational' || $state === 'printing',
                    'danger' => static fn ($state): bool => $state === 'offline' || $state === 'error',
                ]),
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
            //
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
        return fn (Printer $printer): string => route('printers.show', ['printer' => $printer]);
    }

    public function render(): View
    {
        return view('livewire.filament.table', [
            'title' => 'Printers',
            'link' => ['route' => 'printers.create', 'label' => 'Create'],
            'breadcrumbs' => [
                ['label' => 'Printers']
            ],
        ]);
    }
}
