<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('actor_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('entity_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'customer' => 'success',
                        'lead' => 'warning',
                        'task' => 'info',
                        'quotation' => 'primary',
                        'conversation' => 'gray',
                        'general' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('details')
                    ->limit(60)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('entity_type')
                    ->options([
                        'customer' => 'Customer',
                        'lead' => 'Lead',
                        'task' => 'Task',
                        'quotation' => 'Quotation',
                        'conversation' => 'Conversation',
                        'setting' => 'Setting',
                        'ai' => 'AI',
                        'general' => 'General',
                    ]),
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
