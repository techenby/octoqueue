<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required(),
                Forms\Components\TextInput::make('brand')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cost')
                    ->maxLength(255),
                Forms\Components\TextInput::make('color')
                    ->maxLength(255),
                Forms\Components\TextInput::make('color_hex')
                    ->maxLength(255),
                Forms\Components\TextInput::make('printer_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('diameter')
                    ->maxLength(255),
                Forms\Components\TextInput::make('empty')
                    ->maxLength(255),
                Forms\Components\TextInput::make('weights'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team.name'),
                Tables\Columns\TextColumn::make('brand'),
                Tables\Columns\TextColumn::make('cost'),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\TextColumn::make('color_hex'),
                Tables\Columns\TextColumn::make('printer_type'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('diameter'),
                Tables\Columns\TextColumn::make('empty'),
                Tables\Columns\TextColumn::make('weights'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }    
}
