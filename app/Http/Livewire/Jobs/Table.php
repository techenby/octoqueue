<?php

namespace App\Http\Livewire\Jobs;

use App\Models\Job;
use App\Models\Material;
use Closure;
use Filament\Tables\Actions\BulkAction;
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
        return Job::query()->whereTeamId(auth()->user()->current_team_id);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name'),
            TextColumn::make('user.name'),
            TextColumn::make('printType.name'),
            TextColumn::make('printer.name'),
            TextColumn::make('material.name'),
            ColorColumn::make('color_hex')->label('Color'),
            TextColumn::make('created_at')->label('Created At')->since(),
            TextColumn::make('started_at')->label('Started At')->since(),
            TextColumn::make('material_used')->label('Material Used'),
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
        return fn (Job $job): string => route('jobs.edit', ['job' => $job]);
    }

    public function render(): View
    {
        return view('livewire.filament.table', [
            'title' => 'Queue',
            'link' => ['route' => 'jobs.create', 'label' => 'Create'],
            'breadcrumbs' => [
                ['label' => 'Queue']
            ],
        ]);
    }
}