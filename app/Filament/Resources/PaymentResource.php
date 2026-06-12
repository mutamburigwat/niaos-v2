<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Customer;
use App\Models\Payment;
use App\Services\WorkspaceContext;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Payment')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn () => Customer::query()->currentWorkspace()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('billing_record_id')
                            ->label('Billing Record')
                            ->options(fn () => \App\Models\BillingRecord::query()->currentWorkspace()->orderBy('title')->pluck('title', 'id'))
                            ->searchable()
                            ->nullable(),
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->prefix('USD'),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'USD',
                                'ZWG' => 'ZWG',
                                'ZAR' => 'ZAR',
                            ])
                            ->default('USD'),
                        Forms\Components\DatePicker::make('payment_date')
                            ->required(),
                        Forms\Components\TextInput::make('payment_method')
                            ->nullable()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('reference')
                            ->nullable()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->rows(2)
                            ->columnSpanFull()
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('payment_date', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([])
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
