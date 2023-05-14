<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages\CreateJob;
use App\Filament\Resources\JobResource\Pages\EditJob;
use App\Filament\Resources\JobResource\Pages\ListJobs;
use App\Models\Job;
use Closure;
use Exception;
use Filament\Forms\Components\Builder as FormBuilder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('print_type_id')
                            ->relationship(
                                'printType',
                                'name',
                                fn (Builder $query) => $query
                                    ->whereTeamId(auth()->user()->current_team_id)->orderBy('priority')
                            )
                            ->label('Print Type')
                            ->required(),
                        Select::make('color_hex')
                            ->disabled(fn (?Job $record) => $record && $record->isDone)
                            ->options(fn ($livewire) => $livewire->colorOptions)
                            ->label('Material Color'),
                        Textarea::make('notes')
                            ->disabled(fn (?Job $record) => $record && $record->isDone)
                            ->maxLength(65535),
                        TextInput::make('quantity')
                            ->default(1)
                            ->hidden(fn (?Job $record) => $record !== null)
                            ->minValue(1)
                            ->numeric(),
                    ])
                    ->columnSpan(['lg' => 1]),
                FormBuilder::make('files')
                    ->disabled(fn (?Job $record) => $record && $record->isDone)
                    ->blocks([
                        Block::make('existing')
                            ->schema([
                                Select::make('printer')
                                    ->options(fn ($livewire) => $livewire->printers->pluck('name', 'id'))
                                    ->reactive()
                                    ->required(),
                                Select::make('file')
                                    ->options(function (Closure $get, $livewire) {
                                        if ($get('printer') === null) {
                                            return;
                                        }

                                        return $livewire->printers->find($get('printer'))->printableFiles();
                                    })
                                    ->searchable()
                                    ->required(),
                            ]),
                        Block::make('upload')
                            ->schema([
                                Select::make('printer')
                                    ->options(fn ($livewire) => $livewire->printers->pluck('name', 'id'))
                                    ->reactive()
                                    ->required(),
                                TextInput::make('folder')
                                    ->datalist(function (Closure $get, $livewire) {
                                        if ($get('printer') === null) {
                                            return;
                                        }

                                        return $livewire->printers->find($get('printer'))->folders();
                                    }),
                                FileUpload::make('attachment')
                                    ->disk('local')
                                    ->directory(fn (Closure $get) => 'job-attachments/' . $get('printer'))
                                    ->preserveFilenames(),
                                Checkbox::make('maintain_filename')
                                    ->default(true)
                                    ->reactive(),
                                TextInput::make('filename')
                                    ->hidden(fn (Closure $get) => $get('maintain_filename') == true),
                            ]),
                    ])
                    ->cloneable()
                    ->collapsible()
                    ->maxItems(fn ($livewire) => $livewire->printers->count())
                    ->required()
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('failed_at')
                    ->label('Failed At')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('material_used')
                    ->label('Material Used')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Filter::make('to_print')
                    ->query(fn (Builder $query): Builder => $query->orWhere(fn ($query) => $query->whereNull('started_at')->whereNull('failed_at')->whereNull('completed_at'))),
                Filter::make('has_started')
                    ->query(fn (Builder $query): Builder => $query->orWhere(fn ($query) => $query->whereNotNull('started_at')->whereNull('failed_at')->whereNull('completed_at'))),
                Filter::make('has_completed')
                    ->query(fn (Builder $query): Builder => $query->orWhere(fn ($query) => $query->whereNotNull('started_at')->whereNull('failed_at')->whereNotNull('completed_at'))),
                Filter::make('has_failed')
                    ->query(fn (Builder $query): Builder => $query->orWhere(fn ($query) => $query->whereNotNull('started_at')->whereNotNull('failed_at')->whereNull('completed_at'))),
            ])
            ->actions([
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
                    })
                    ->hidden(fn (Job $record) => $record->hasStarted),
                Action::make('done')
                    ->action(function (Job $record) {
                        try {
                            $record->markAsComplete();
                        } catch (Exception $e) {
                            Notification::make()
                                ->title($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->hidden(fn (Job $record) => $record->isDone || ! $record->hasStarted),
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
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereType(Job::class)
            ->whereTeamId(auth()->user()->current_team_id ?? auth()->user()->currentTeam->id);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobs::route('/'),
            'create' => CreateJob::route('/create'),
            'edit' => EditJob::route('/{record}/edit'),
        ];
    }
}
