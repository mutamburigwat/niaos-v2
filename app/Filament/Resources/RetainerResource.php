<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RetainerResource\Pages;
use App\Models\Retainer;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
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
                Schemas\Components\Section::make('Retainer Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('service_id')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Select::make('billing_cycle')
                            ->options([
                                'monthly' => 'Monthly',
                                'quarterly' => 'Quarterly',
                                'yearly' => 'Yearly',
                                'custom' => 'Custom',
                            ])
                            ->default('monthly'),
                    ])
                    ->columns(2),
                Schemas\Components\Section::make('Pricing')
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
                        Forms\Components\Placeholder::make('_empty')
                            ->hiddenLabel(),
                    ])
                    ->columns(3),
                Schemas\Components\Section::make('Dates')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        Forms\Components\DatePicker::make('next_billing_date')
                            ->nullable(),
                        Forms\Components\Placeholder::make('_empty2')
                            ->hiddenLabel(),
                    ])
                    ->columns(3),
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
                    ->sortable(),
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
                    ->sortable(),
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
                Actions\ActionGroup::make([
                    Actions\EditAction::make(),
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
