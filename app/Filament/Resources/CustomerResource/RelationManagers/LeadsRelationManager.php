<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class LeadsRelationManager extends RelationManager
{
    protected static string $relationship = 'leads';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('stage')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'quotation_sent' => 'Quotation Sent',
                        'followup' => 'Follow-Up',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ])
                    ->default('new'),
                Forms\Components\TextInput::make('estimated_value')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                Forms\Components\Select::make('priority')
                    ->options([
                        'high' => 'High',
                        'medium' => 'Medium',
                        'low' => 'Low',
                    ])
                    ->default('medium'),
                Forms\Components\DateTimePicker::make('next_follow_up_date'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'contacted' => 'primary',
                        'qualified' => 'warning',
                        'quotation_sent' => 'warning',
                        'followup' => 'gray',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('estimated_value')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('next_follow_up_date')
                    ->dateTime()
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
