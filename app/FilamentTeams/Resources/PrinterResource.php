<?php

namespace App\FilamentTeams\Resources;

use App\FilamentTeams\Resources\PrinterResource\Pages\CreatePrinter;
use App\FilamentTeams\Resources\PrinterResource\Pages\EditPrinter;
use App\FilamentTeams\Resources\PrinterResource\Pages\ListPrinters;
use App\FilamentTeams\Resources\PrinterResource\Pages\ViewPrinter;
use App\Jobs\FetchPrinterStatus;
use App\Models\Printer;
use Artificertech\FilamentMultiContext\Concerns\ContextualResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class PrinterResource extends Resource
{
    use ContextualResource;

    protected static ?string $model = Printer::class;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('model'),
                        TextInput::make('url')
                            ->label('URL')
                            ->required()
                            ->url(),
                        TextInput::make('api_key')
                            ->label('API Key')
                            ->password()
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('model')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'closed',
                        'success' => static fn ($state): bool => $state === 'operational' || $state === 'printing',
                        'danger' => static fn ($state): bool => $state === 'offline' || $state === 'error',
                    ])
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('fetch_status')
                    ->action(fn (Printer $record) => FetchPrinterStatus::dispatch($record)),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                BulkAction::make('fetch-printer-status')
                    ->label('Fetch Status')
                    ->action(fn (Printer $record) => FetchPrinterStatus::dispatch($record)),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereTeamId(auth()->user()->current_team_id);
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
            'index' => ListPrinters::route('/'),
            'create' => CreatePrinter::route('/create'),
            'view' => ViewPrinter::route('/{record}'),
            'edit' => EditPrinter::route('/{record}/edit'),
        ];
    }
}
