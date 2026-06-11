<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RetainerResource\Pages;
use App\Models\Customer;
use App\Models\Retainer;
use App\Models\Service;
use App\Services\WorkspaceContext;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RetainerResource extends Resource
{
    protected static ?string $model = Retainer::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                    ->schema([
                        Schemas\Components\Section::make('Retainer Details')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('customer_id')
                                    ->label('Customer')
                                    ->options(fn () => Customer::query()->currentWorkspace()->orderBy('name')->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\Select::make('service_id')
                                    ->label('Service')
                                    ->options(fn () => Service::query()->currentWorkspace()->orderBy('name')->pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),
                                Forms\Components\Select::make('billing_cycle')
                                    ->label('Billing Cycle')
                                    ->options([
                                        'monthly' => 'Monthly',
                                        'quarterly' => 'Quarterly',
                                        'yearly' => 'Yearly',
                                        'custom' => 'Custom',
                                    ])
                                    ->default('monthly'),
                            ]),
                        Schemas\Components\Section::make('Pricing & Dates')
                            ->columnSpan(1)
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('USD')
                                    ->default(0),
                                Forms\Components\Select::make('currency')
                                    ->options([
                                        'USD' => 'USD',
                                        'ZWG' => 'ZWG',
                                        'ZAR' => 'ZAR',
                                    ])
                                    ->default('USD'),
                                Forms\Components\DatePicker::make('start_date')
                                    ->required(),
                                Forms\Components\DatePicker::make('next_billing_date')
                                    ->label('Next Billing Date')
                                    ->nullable(),
                            ]),
                    ]),
                Schemas\Components\Section::make('Status & Notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'paused' => 'Paused',
                                'cancelled' => 'Cancelled',
                                'overdue' => 'Overdue',
                            ])
                            ->default('active'),
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Retainer $record): string => RetainerResource::getUrl('edit', ['record' => $record])),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'paused' => 'warning',
                        'cancelled' => 'gray',
                        'overdue' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('next_billing_date')
                    ->date()
                    ->sortable()
                    ->color(fn (?Retainer $record): string => match (true) {
                        $record?->next_billing_date === null => 'gray',
                        $record->next_billing_date->isPast() && in_array($record->status, ['active', 'overdue']) => 'danger',
                        $record->next_billing_date->isToday() => 'warning',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'paused' => 'Paused',
                        'cancelled' => 'Cancelled',
                        'overdue' => 'Overdue',
                    ]),
                Tables\Filters\SelectFilter::make('billing_cycle')
                    ->options([
                        'monthly' => 'Monthly',
                        'quarterly' => 'Quarterly',
                        'yearly' => 'Yearly',
                        'custom' => 'Custom',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\ActionGroup::make([
                    Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRetainers::route('/'),
            'create' => Pages\CreateRetainer::route('/create'),
            'edit' => Pages\EditRetainer::route('/{record}/edit'),
        ];
    }
}
