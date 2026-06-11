<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\Customer;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class RetainersRelationManager extends RelationManager
{
    protected static string $relationship = 'retainers';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                Forms\Components\Select::make('billing_cycle')
                    ->options([
                        'monthly' => 'Monthly',
                        'quarterly' => 'Quarterly',
                        'yearly' => 'Yearly',
                        'custom' => 'Custom',
                    ])
                    ->default('monthly'),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'paused' => 'Paused',
                        'cancelled' => 'Cancelled',
                        'overdue' => 'Overdue',
                    ])
                    ->default('active'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('next_billing_date')
                    ->date()
                    ->sortable(),
            ])
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
