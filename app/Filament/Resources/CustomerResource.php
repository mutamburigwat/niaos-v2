<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\Services\WorkspaceContext;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('company_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Schemas\Components\Section::make('Classification')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'customer' => 'Customer',
                                'lead' => 'Lead',
                                'dormant' => 'Dormant',
                            ])
                            ->default('customer'),
                        Forms\Components\Select::make('source_channel')
                            ->options([
                                'whatsapp' => 'WhatsApp',
                                'instagram' => 'Instagram',
                                'facebook' => 'Facebook',
                                'website' => 'Website',
                                'referral' => 'Referral',
                                'other' => 'Other',
                            ])
                            ->default('website'),
                        Forms\Components\TagsInput::make('tags'),
                        Forms\Components\Select::make('assigned_to')
                            ->options(fn () => User::whereHas('workspaceMembers', fn ($q) => $q->where('workspace_id', WorkspaceContext::activeWorkspaceId())->where('status', 'active'))->orderBy('name')->pluck('name', 'id'))
                            ->searchable(),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'customer' => 'success',
                        'lead' => 'warning',
                        'dormant' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('source_channel')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tags')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('assignedStaff.name')
                    ->label('Assigned To')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('last_interaction_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
                        'customer' => 'Customer',
                        'lead' => 'Lead',
                        'dormant' => 'Dormant',
                    ]),
                Tables\Filters\SelectFilter::make('source_channel')
                    ->options([
                        'whatsapp' => 'WhatsApp',
                        'instagram' => 'Instagram',
                        'facebook' => 'Facebook',
                        'website' => 'Website',
                        'referral' => 'Referral',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->options(fn () => User::whereHas('workspaceMembers', fn ($q) => $q->where('workspace_id', WorkspaceContext::activeWorkspaceId())->where('status', 'active'))->orderBy('name')->pluck('name', 'id')),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\EditAction::make(),
                    Actions\Action::make('create_lead')
                        ->label('Create Lead')
                        ->icon('heroicon-o-arrow-trending-up')
                        ->color('warning')
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->label('Lead Title')
                                ->required()
                                ->maxLength(255),
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
                        ->action(function (Customer $record, array $data) {
                            $data['customer_id'] = $record->id;
                            $data['workspace_id'] = $record->workspace_id;
                            if (auth()->user()?->active_workspace_id) {
                                $data['workspace_id'] = auth()->user()->active_workspace_id;
                            }
                            Lead::create($data);
                        }),
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
                        ->action(function (Customer $record, array $data) {
                            $data['customer_id'] = $record->id;
                            $data['workspace_id'] = $record->workspace_id;
                            if (auth()->user()?->active_workspace_id) {
                                $data['workspace_id'] = auth()->user()->active_workspace_id;
                            }
                            $data['status'] = 'pending';
                            Task::create($data);
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

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\CustomerResource\RelationManagers\LeadsRelationManager::class,
            \App\Filament\Resources\CustomerResource\RelationManagers\TasksRelationManager::class,
            \App\Filament\Resources\CustomerResource\RelationManagers\QuotationsRelationManager::class,
            \App\Filament\Resources\CustomerResource\RelationManagers\RetainersRelationManager::class,
            \App\Filament\Resources\CustomerResource\RelationManagers\SupportRequestsRelationManager::class,
            \App\Filament\Resources\CustomerResource\RelationManagers\ContactsRelationManager::class,
            \App\Filament\Resources\CustomerResource\RelationManagers\BillingRecordsRelationManager::class,
            \App\Filament\Resources\CustomerResource\RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
