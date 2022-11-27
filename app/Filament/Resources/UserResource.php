<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('two_factor_secret')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('two_factor_recovery_codes')
                    ->maxLength(65535),
                Forms\Components\DateTimePicker::make('two_factor_confirmed_at'),
                Forms\Components\TextInput::make('stripe_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pm_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pm_last_four')
                    ->maxLength(4),
                Forms\Components\TextInput::make('pm_expiration')
                    ->maxLength(255),
                Forms\Components\Textarea::make('extra_billing_information')
                    ->maxLength(65535),
                Forms\Components\DateTimePicker::make('trial_ends_at'),
                Forms\Components\TextInput::make('billing_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_address_line_2')
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_city')
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_state')
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_postal_code')
                    ->maxLength(25),
                Forms\Components\TextInput::make('vat_id')
                    ->maxLength(50),
                Forms\Components\Textarea::make('receipt_emails')
                    ->maxLength(65535),
                Forms\Components\TextInput::make('billing_country')
                    ->maxLength(2),
                Forms\Components\Select::make('current_team_id')
                    ->relationship('currentTeam', 'name'),
                Forms\Components\TextInput::make('profile_photo_path')
                    ->maxLength(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('currentTeam.name'),
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
                Impersonate::make('impersonate'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
