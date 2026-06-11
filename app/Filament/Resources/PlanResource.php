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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Plan Identity')
                    ->description('Basic information that identifies this plan.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique key like "starter", "growth", "business".'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
                Schemas\Components\Section::make('Pricing')
                    ->description('Monetary configuration for this plan.')
                    ->columns(3)
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
                    ]),
                Schemas\Components\Section::make('Availability')
                    ->description('Control whether this plan is available and how it is ordered.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ]),
                Schemas\Components\Section::make('Features')
                    ->description('Key-value pairs describing what this plan includes. The key is the module identifier.')
                    ->schema([
                        Forms\Components\KeyValue::make('features')
                            ->label('Features')
                            ->columnSpanFull(),
                    ]),
                Schemas\Components\Section::make('Limits')
                    ->description('Set numeric caps per resource. Use -1 for unlimited, 0 to disable.')
                    ->schema([
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
                    ->label('Interval')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'none' => 'gray',
                        'monthly' => 'success',
                        'yearly' => 'info',
                        'custom' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'none' => 'Free',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                        'custom' => 'Custom',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Actions\EditAction::make(),
                Actions\ActionGroup::make([
                    Actions\Action::make('toggle_active')
                        ->label(fn (Plan $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (Plan $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (Plan $record): string => $record->is_active ? 'danger' : 'success')
                        ->action(fn (Plan $record) => $record->update(['is_active' => ! $record->is_active])),
                ]),
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
