<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use Facades\App\Calculator;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-color-swatch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('brand')
                            ->required(),
                        TextInput::make('cost')
                            ->mask(fn (Mask $mask) => $mask->money(prefix: '$', thousandsSeparator: ',', decimalPlaces: 2, isSigned: false))
                            ->required(),
                        TextInput::make('color')
                            ->required(),
                        ColorPicker::make('color_hex')
                            ->label('Color HEX')
                            ->required(),
                        Radio::make('printer_type')
                            ->label('Printer Type')
                            ->options([
                                'fdm' => 'FDM',
                                'sla' => 'SLA',
                            ])
                            ->lazy(),
                        TextInput::make('type')
                            ->datalist(fn ($get) => Calculator::materialByType($get('printer_type')))
                            ->hidden(fn ($get) => $get('printer_type') === null)
                            ->required(),
                        TextInput::make('diameter')
                            ->hidden(fn ($get) => $get('printer_type') === null || $get('printer_type') === 'sla'),
                        TextInput::make('empty')
                            ->hidden(fn ($get) => $get('printer_type') === null)
                            ->label(fn ($get) => $get('printer_type') === 'fdm' ? 'Empty Spool Weight' : 'Empty Bottle Weight'),
                        TextInput::make('current_weight')
                            ->label('Current Weight'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                ReplicateAction::make()
                    ->excludeAttributes(['weights']),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
