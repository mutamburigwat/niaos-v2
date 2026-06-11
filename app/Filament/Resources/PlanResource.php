<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Plan Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique identifier like "starter", "growth", "business".'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
                Schemas\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('price_amount')
                            ->label('Price')
                            ->numeric()
                            ->prefix('USD')
                            ->nullable(),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'USD',
                                'ZWG' => 'ZWG',
                                'ZAR' => 'ZAR',
                            ])
                            ->default('USD'),
                        Forms\Components\Select::make('billing_interval')
                            ->options([
                                'none' => 'None (Free)',
                                'monthly' => 'Monthly',
                                'yearly' => 'Yearly',
                                'custom' => 'Custom',
                            ])
                            ->default('none')
                            ->required(),
                    ])->columns(3),
                Schemas\Components\Section::make('Status & Order')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])->columns(2),
                Schemas\Components\Section::make('Features & Limits')
                    ->schema([
                        Forms\Components\KeyValue::make('features')
                            ->label('Features')
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('limits')
                            ->label('Limits')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_amount')
                    ->label('Price')
                    ->money('USD')
                    ->default('Free'),
                Tables\Columns\TextColumn::make('billing_interval')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'none' => 'gray',
                        'monthly' => 'success',
                        'yearly' => 'info',
                        'custom' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Actions\EditAction::make(),
                Actions\Action::make('toggle_active')
                    ->label(fn (Plan $record): string => $record->is_active ? 'Disable' : 'Enable')
                    ->icon(fn (Plan $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Plan $record): string => $record->is_active ? 'danger' : 'success')
                    ->action(fn (Plan $record) => $record->update(['is_active' => ! $record->is_active])),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->isPlatformAdmin() ?? false;
    }
}
