<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Closure;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Table extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return Printer::query()->whereTeamId(auth()->user()->current_team_id);
    }

    protected function getTableColumns(): array

    {
        return [
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('model'),
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
            Tables\Actions\BulkAction::make('delete')
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
        return fn (Printer $printer): string => route('printers.edit', ['printer' => $printer]);
    }

    public function render(): View
    {
        return view('livewire.printers.table');
    }
}
