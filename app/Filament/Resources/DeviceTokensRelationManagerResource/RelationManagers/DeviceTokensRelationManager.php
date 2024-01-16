<?php

namespace App\Filament\Resources\DeviceTokensRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class DeviceTokensRelationManager extends RelationManager
{
    protected static string $relationship = 'deviceTokens';

    protected static ?string $recordTitleAttribute = 'token';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('token')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('token')
                    ->copyable()
                    ->copyMessage('Copied Token to Clipboard!')
                    ->color('primary')
                    ->limit(25),
                Tables\Columns\TextColumn::make('updated_at'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
