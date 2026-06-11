<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-wrench';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Service Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->options([
                                'Digital Presence' => 'Digital Presence',
                                'Business Systems' => 'Business Systems',
                                'Growth Infrastructure' => 'Growth Infrastructure',
                                'Website Maintenance' => 'Website Maintenance',
                                'Social Media Design' => 'Social Media Design',
                                'Business Email Setup' => 'Business Email Setup',
                                'CRM Setup' => 'CRM Setup',
                                'Monthly Digital Support' => 'Monthly Digital Support',
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Schemas\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\Select::make('pricing_type')
                            ->options([
                                'once_off' => 'Once Off',
                                'monthly' => 'Monthly',
                                'yearly' => 'Yearly',
                                'custom' => 'Custom',
                            ]),
                        Forms\Components\TextInput::make('base_price')
                            ->numeric()
                            ->prefix('USD'),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'USD',
                                'ZWG' => 'ZWG',
                                'ZAR' => 'ZAR',
                            ])
                            ->default('USD'),
                    ])
                    ->columns(3),
                Schemas\Components\Section::make('Status & Notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pricing_type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
