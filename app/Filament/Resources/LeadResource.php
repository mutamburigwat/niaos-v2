<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Task;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-trending-up';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Lead Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                            ]),
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
                        Forms\Components\Select::make('priority')
                            ->options([
                                'high' => 'High',
                                'medium' => 'Medium',
                                'low' => 'Low',
                            ])
                            ->default('medium'),
                    ])
                    ->columns(2),
                Schemas\Components\Section::make('Value & Source')
                    ->schema([
                        Forms\Components\TextInput::make('estimated_value')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\Select::make('source_channel')
                            ->options([
                                'whatsapp' => 'WhatsApp',
                                'instagram' => 'Instagram',
                                'facebook' => 'Facebook',
                                'website' => 'Website',
                                'referral' => 'Referral',
                                'other' => 'Other',
                            ]),
                        Forms\Components\Select::make('assigned_to')
                            ->relationship('assignedStaff', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\DateTimePicker::make('next_follow_up_date'),
                    ])
                    ->columns(2),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
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
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('estimated_value')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('next_follow_up_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedStaff.name')
                    ->label('Assigned To')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([
                Tables\Filters\SelectFilter::make('stage')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'quotation_sent' => 'Quotation Sent',
                        'followup' => 'Follow-Up',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'high' => 'High',
                        'medium' => 'Medium',
                        'low' => 'Low',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->relationship('assignedStaff', 'name'),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\EditAction::make(),
                    Actions\Action::make('create_task')
                        ->label('Create Task')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Select::make('priority')
                                ->options([
                                    'high' => 'High',
                                    'medium' => 'Medium',
                                    'low' => 'Low',
                                ])
                                ->default('medium'),
                            Forms\Components\DateTimePicker::make('due_date'),
                        ])
                        ->action(function (Lead $record, array $data) {
                            $data['lead_id'] = $record->id;
                            $data['customer_id'] = $record->customer_id;
                            $data['workspace_id'] = $record->workspace_id;
                            $data['status'] = 'pending';
                            Task::create($data);
                        }),
                    Actions\Action::make('create_quotation')
                        ->label('Create Quotation')
                        ->icon('heroicon-o-document-text')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('quote_number')
                                ->required()
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
                        ->action(function (Lead $record, array $data) {
                            $data['customer_id'] = $record->customer_id;
                            $data['lead_id'] = $record->id;
                            $data['workspace_id'] = $record->workspace_id;
                            $data['subtotal'] = 0;
                            $data['discount'] = 0;
                            $data['tax'] = 0;
                            $data['total'] = 0;
                            $data['currency'] = 'USD';
                            Quotation::create($data);
                        }),
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
