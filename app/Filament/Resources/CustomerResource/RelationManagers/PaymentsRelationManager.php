<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';
    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->prefix('$'),
                Forms\Components\DatePicker::make('payment_date')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),
            ])
            ->defaultSort('payment_date', 'desc')
            ->filters([])
            ->headerActions([
                Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => [
                        ...$data,
                        'workspace_id' => $this->ownerRecord->workspace_id,
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
