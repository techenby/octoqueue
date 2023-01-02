<?php

namespace App\FilamentTeams\Resources;

use App\FilamentTeams\Resources\JobResource\Pages;
use App\Models\Job;
use Artificertech\FilamentMultiContext\Concerns\ContextualResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class JobResource extends Resource
{
    use ContextualResource;

    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('print_type_id')
                    ->relationship('printType', 'name')
                    ->required(),
                Forms\Components\Select::make('printer_id')
                    ->relationship('printer', 'name'),
                Forms\Components\Select::make('material_id')
                    ->relationship('material', 'id'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('color_hex')
                    ->maxLength(255),
                Forms\Components\TextInput::make('files'),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535),
                Forms\Components\DateTimePicker::make('started_at'),
                Forms\Components\DateTimePicker::make('completed_at'),
                Forms\Components\DateTimePicker::make('failed_at'),
                Forms\Components\TextInput::make('material_used'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team.name'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('printType.name'),
                Tables\Columns\TextColumn::make('printer.name'),
                Tables\Columns\TextColumn::make('material.id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('color_hex'),
                Tables\Columns\TextColumn::make('files'),
                Tables\Columns\TextColumn::make('notes'),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('failed_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('material_used'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
