<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceTokensRelationManagerResource\RelationManagers\DeviceTokensRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        $createAndEdit = [
            Pages\CreateUser::class,
            Pages\EditUser::class,
        ];

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->hiddenOn($createAndEdit),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('current_team_id')
                    ->hiddenOn($createAndEdit),

                Forms\Components\Textarea::make('profile_photo_path')
                    ->hiddenOn($createAndEdit)
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('email'),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->options([
                        'heroicon-o-x-mark' => fn ($state): bool => ! is_null($state),
                        'heroicon-o-check' => fn ($state): bool => is_null($state),
                    ]),

                Tables\Columns\TextColumn::make('teams_count')
                    ->counts('teams')
                    ->label('Teams'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /** @return class-string[] */
    public static function getRelations(): array
    {
        return [
            DeviceTokensRelationManager::class,
        ];
    }

    /** @return array<string, array<mixed>> */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
