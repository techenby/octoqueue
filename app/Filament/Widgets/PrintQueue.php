<?php

namespace App\Filament\Widgets;

use App\Models\Job;
use Exception;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PrintQueue extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return Job::forCurrentTeam()
            ->where('type', Job::class)
            ->whereNull('started_at')
            ->whereNull('failed_at')
            ->whereNull('completed_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->sortable()
                ->searchable(),
            TextColumn::make('user.name')
                ->sortable(),
            TextColumn::make('printType.name')
                ->label('Type')
                ->sortable(),
            ColorColumn::make('color_hex')
                ->label('Color')
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Created At')
                ->since()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('print')
                ->action(function (Job $record) {
                    try {
                        $record->print();
                    } catch (Exception $e) {
                        Notification::make()
                            ->title($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            ActionGroup::make([
                EditAction::make(),
                DeleteAction::make(),
            ]),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('color_hex')
                ->label('Color')
                ->multiple()
                ->options(auth()->user()->currentTeam->materials->pluck('name', 'color_hex')),
            SelectFilter::make('printType')->relationship('printType', 'name'),
            SelectFilter::make('user')->relationship('user', 'name'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(route('filament.resources.jobs.create')),
        ];
    }
}
