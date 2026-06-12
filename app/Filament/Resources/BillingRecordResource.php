<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillingRecordResource\Pages;
use App\Models\BillingRecord;
use App\Models\Customer;
use App\Services\WorkspaceContext;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BillingRecordResource extends Resource
{
    protected static ?string $model = BillingRecord::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Billing Record')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn () => Customer::query()->currentWorkspace()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('retainer_id')
                            ->label('Retainer')
                            ->options(fn () => \App\Models\Retainer::query()->currentWorkspace()->orderBy('title')->pluck('title', 'id'))
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
                        Forms\Components\DatePicker::make('issue_date')
                            ->required(),
                        Forms\Components\DatePicker::make('due_date')
                            ->nullable(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'issued' => 'Issued',
                                'partially_paid' => 'Partially Paid',
                                'paid' => 'Paid',
                                'overdue' => 'Overdue',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft'),
                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull()
                            ->nullable(),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->url(fn (BillingRecord $record): string => BillingRecordResource::getUrl('edit', ['record' => $record])),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'issued' => 'warning',
                        'partially_paid' => 'info',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'issued' => 'Issued',
                        'partially_paid' => 'Partially Paid',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBillingRecords::route('/'),
            'create' => Pages\CreateBillingRecord::route('/create'),
            'edit' => Pages\EditBillingRecord::route('/{record}/edit'),
        ];
    }
}
