<?php

namespace App\Http\Livewire\Jobs;

use App\Models\Job;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
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
            TextColumn::make('name')
                ->sortable()
                ->searchable(),
            TextColumn::make('user.name')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('printType.name')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('printer.name')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('material.name')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ColorColumn::make('color_hex')
                ->label('Color')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('created_at')
                ->label('Created At')
                ->since()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('started_at')
                ->label('Started At')
                ->since()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
            TextColumn::make('material_used')
                ->label('Material Used')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('to_print')
                ->query(fn (Builder $query): Builder => $query->orWhere(fn ($query) => $query->whereNull('started_at')->whereNull('failed_at')->whereNull('completed_at')))
                ->default(),
            Filter::make('has_started')
                ->query(fn (Builder $query): Builder => $query->orWhere(fn ($query) => $query->whereNotNull('started_at')->whereNull('failed_at')->whereNull('completed_at'))),
            Filter::make('has_completed')
                ->query(fn (Builder $query): Builder => $query->orWhere(fn ($query) => $query->whereNotNull('started_at')->whereNull('failed_at')->whereNotNull('completed_at'))),
            Filter::make('has_failed')
                ->query(fn (Builder $query): Builder => $query->orWhere(fn ($query) => $query->whereNotNull('started_at')->whereNotNull('failed_at')->whereNull('completed_at'))),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('print')
                ->action(function (Job $record) {
                    try {
                        $record->print();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('duplicate')
                ->form([
                    TextInput::make('times')
                        ->numeric(),
                ])
                ->action(function (Job $record, array $data): void {
                    foreach (range(1, $data['times']) as $time) {
                        $record->copy();
                    }
                }),
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
            BulkAction::make('duplicate')
                ->label('Duplicate selected')
                ->form([
                    Select::make('color_hex')
                        ->options(auth()->user()->currentTeam->materials->pluck('color', 'color_hex'))
                        ->label('Material Color'),
                ])
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->copy($data['color_hex']);
                    }
                }),
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
                ['label' => 'Queue'],
            ],
        ]);
    }
}
