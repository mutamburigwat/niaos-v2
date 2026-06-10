<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Models\Quotation;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Client & Reference')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('lead_id')
                            ->relationship('lead', 'title')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('quote_number')
                            ->required()
                            ->maxLength(255)
                            ->default(fn () => 'QTE-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4))),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ])
                            ->default('draft'),
                    ])
                    ->columns(2),
                Schemas\Components\Section::make('Line Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('description')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        $qty = (float) ($get('quantity') ?? 0);
                                        $price = (float) ($get('unit_price') ?? 0);
                                        $set('total', round($qty * $price, 2));
                                    }),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        $qty = (float) ($get('quantity') ?? 0);
                                        $price = (float) ($get('unit_price') ?? 0);
                                        $set('total', round($qty * $price, 2));
                                    }),
                                Forms\Components\TextInput::make('total')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled(),
                            ])
                            ->columns(4)
                            ->columnSpanFull()
                            ->addActionLabel('Add Item')
                            ->live()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $items = $get('items') ?? [];
                                $subtotal = 0;
                                foreach ($items as $item) {
                                    $subtotal += (float) ($item['total'] ?? 0);
                                }
                                $set('subtotal', round($subtotal, 2));
                                $discount = (float) ($get('discount') ?? 0);
                                $taxRate = (float) ($get('tax') ?? 0);
                                $afterDiscount = $subtotal - $discount;
                                $taxAmount = $afterDiscount * ($taxRate / 100);
                                $set('total', round($afterDiscount + $taxAmount, 2));
                            }),
                    ]),
                Schemas\Components\Section::make('Financials')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled(),
                        Forms\Components\TextInput::make('discount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $subtotal = (float) ($get('subtotal') ?? 0);
                                $discount = (float) ($get('discount') ?? 0);
                                $taxRate = (float) ($get('tax') ?? 0);
                                $afterDiscount = $subtotal - $discount;
                                $taxAmount = $afterDiscount * ($taxRate / 100);
                                $set('total', round($afterDiscount + $taxAmount, 2));
                            }),
                        Forms\Components\TextInput::make('tax')
                            ->numeric()
                            ->suffix('%')
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $subtotal = (float) ($get('subtotal') ?? 0);
                                $discount = (float) ($get('discount') ?? 0);
                                $taxRate = (float) ($get('tax') ?? 0);
                                $afterDiscount = $subtotal - $discount;
                                $taxAmount = $afterDiscount * ($taxRate / 100);
                                $set('total', round($afterDiscount + $taxAmount, 2));
                            }),
                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled(),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'USD',
                                'ZWG' => 'ZWG',
                                'KES' => 'KES',
                                'ZAR' => 'ZAR',
                            ])
                            ->default('USD'),
                        Forms\Components\DatePicker::make('valid_until'),
                    ])
                    ->columns(3),
                Schemas\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quote_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lead.title')
                    ->label('Lead')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'primary',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_until')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Quotation $record): string => route('quotations.print', $record))
                    ->openUrlInNewTab(),
                Actions\DeleteAction::make(),
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
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
