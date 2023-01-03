<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job;
use Closure;
use Filament\Forms\Components\Builder as FormBuilder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Contracts\Database\Eloquent\Builder;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('print_type_id')
                            ->relationship('printType', 'name',
                                fn (Builder $query) => $query
                                    ->whereTeamId(auth()->user()->current_team_id)->orderBy('priority')
                            )
                            ->label('Print Type')
                            ->required(),
                        Select::make('color_hex')
                            ->options(fn ($livewire) => $livewire->colorOptions)
                            ->label('Material Color'),
                        Textarea::make('notes')
                            ->maxLength(65535),
                    ])
                    ->columnSpan(['lg' => 1]),
                FormBuilder::make('files')
                    ->blocks([
                        Block::make('choose')
                            ->icon('heroicon-o-cursor-click')
                            ->label('Choose File from Printer')
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
                            ->icon('heroicon-o-upload')
                            ->label('Upload file to Printer')
                            ->schema([
                                Select::make('printer')
                                    ->options(fn ($livewire) => $livewire->printers->pluck('name', 'id'))
                                    ->reactive()
                                    ->required(),
                                Select::make('folder')
                                    ->options(function (Closure $get) {
                                        if ($get('printer') === null) {
                                            return;
                                        }

                                        return $this->printers->find($get('printer'))->folders();
                                    })
                                    ->searchable()
                                    ->required(),
                                FileUpload::make('attachment')->preserveFilenames()->required(),
                            ]),
                    ])
                    ->cloneable()
                    ->collapsible()
                    ->createItemButtonLabel('Choose or Upload Files to Print')
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
                TextColumn::make('user.name'),
                TextColumn::make('printType.name'),
                TextColumn::make('printer.name'),
                TextColumn::make('material.name'),
                TextColumn::make('name'),
                TextColumn::make('color_hex'),
                TextColumn::make('files'),
                TextColumn::make('notes'),
                TextColumn::make('started_at')
                    ->dateTime(),
                TextColumn::make('completed_at')
                    ->dateTime(),
                TextColumn::make('failed_at')
                    ->dateTime(),
                TextColumn::make('material_used'),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
