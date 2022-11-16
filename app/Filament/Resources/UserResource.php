<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Tables\Columns\TextColumn::make('two_factor_secret'),
                Tables\Columns\TextColumn::make('two_factor_recovery_codes'),
                Tables\Columns\TextColumn::make('two_factor_confirmed_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('stripe_id'),
                Tables\Columns\TextColumn::make('pm_type'),
                Tables\Columns\TextColumn::make('pm_last_four'),
                Tables\Columns\TextColumn::make('pm_expiration'),
                Tables\Columns\TextColumn::make('extra_billing_information'),
                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('billing_address'),
                Tables\Columns\TextColumn::make('billing_address_line_2'),
                Tables\Columns\TextColumn::make('billing_city'),
                Tables\Columns\TextColumn::make('billing_state'),
                Tables\Columns\TextColumn::make('billing_postal_code'),
                Tables\Columns\TextColumn::make('vat_id'),
                Tables\Columns\TextColumn::make('receipt_emails'),
                Tables\Columns\TextColumn::make('billing_country'),
                Tables\Columns\TextColumn::make('currentTeam.name'),
                Tables\Columns\TextColumn::make('profile_photo_path'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
