<?php

namespace App\Filament\Resources;

use App\Enums\BillingStatus;
use App\Enums\Plan;
use App\Enums\WorkspaceStatus;
use App\Enums\WorkspaceType;
use App\Filament\Resources\WorkspaceResource\Pages;
use App\Filament\Resources\WorkspaceResource\RelationManagers\MembersRelationManager;
use App\Models\User;
use App\Models\Workspace;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WorkspaceResource extends Resource
{
    protected static ?string $model = Workspace::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Plan Overview')
                    ->schema([
                        Forms\Components\View::make('filament.components.plan-summary')
                            ->viewData(fn ($record) => ['workspace' => $record]),
                    ])
                    ->visible(fn ($record) => $record !== null),
                Schemas\Components\Section::make('Business Information')
                    ->schema([
                        Forms\Components\TextInput::make('business_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_type')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('industry')
                            ->maxLength(255),
                    ])->columns(2),
                Schemas\Components\Section::make('Location')
                    ->schema([
                        Forms\Components\TextInput::make('country')
                            ->default('Zimbabwe')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->columnSpanFull(),
                    ])->columns(2),
                Schemas\Components\Section::make('Platform Settings')
                    ->schema([
                        Forms\Components\Select::make('workspace_type')
                            ->options(collect(WorkspaceType::cases())->mapWithKeys(fn ($t) => [$t->value => ucfirst(str_replace('_', ' ', $t->value))]))
                            ->default('paid_client')
                            ->visible(fn () => Auth::user()?->isPlatformAdmin() ?? false),
                        Forms\Components\Select::make('plan')
                            ->options(collect(Plan::cases())->mapWithKeys(fn ($p) => [$p->value => ucfirst(str_replace('_', ' ', $p->value))]))
                            ->default('starter')
                            ->visible(fn () => Auth::user()?->isPlatformAdmin() ?? false),
                        Forms\Components\Select::make('billing_status')
                            ->options(collect(BillingStatus::cases())->mapWithKeys(fn ($b) => [$b->value => ucfirst($b->value)]))
                            ->default('trial')
                            ->visible(fn () => Auth::user()?->isPlatformAdmin() ?? false),
                        Forms\Components\Select::make('status')
                            ->options(collect(WorkspaceStatus::cases())->mapWithKeys(fn ($s) => [$s->value => ucfirst($s->value)]))
                            ->default('onboarding')
                            ->visible(fn () => Auth::user()?->isPlatformAdmin() ?? false),
                    ])->columns(2),
                Schemas\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\Select::make('base_currency')
                            ->options([
                                'USD' => 'USD',
                                'ZWG' => 'ZWG',
                                'KES' => 'KES',
                                'ZAR' => 'ZAR',
                            ])
                            ->default('USD'),
                        Forms\Components\Select::make('timezone')
                            ->options([
                                'Africa/Harare' => 'Africa/Harare',
                                'Africa/Nairobi' => 'Africa/Nairobi',
                                'Africa/Johannesburg' => 'Africa/Johannesburg',
                                'Africa/Lagos' => 'Africa/Lagos',
                            ])
                            ->default('Africa/Harare'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->label('Business')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('workspace_type')
                    ->label('Workspace Type')
                    ->badge()
                    ->color(fn (WorkspaceType | string $state): string => match ($state?->value ?? $state) {
                        'internal' => 'info',
                        'paid_client' => 'success',
                        'demo' => 'warning',
                        'partner' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (WorkspaceType | string $state): string => ucfirst(str_replace('_', ' ', $state?->value ?? $state))),
                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->color(fn (Plan | string $state): string => match ($state?->value ?? $state) {
                        'free_internal' => 'info',
                        'starter' => 'gray',
                        'growth' => 'warning',
                        'business' => 'success',
                        'custom' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (Plan | string $state): string => ucfirst(str_replace('_', ' ', $state?->value ?? $state))),
                Tables\Columns\TextColumn::make('billing_status')
                    ->label('Billing')
                    ->badge()
                    ->color(fn (BillingStatus | string $state): string => match ($state?->value ?? $state) {
                        'free' => 'gray',
                        'trial' => 'info',
                        'active' => 'success',
                        'overdue' => 'warning',
                        'suspended' => 'danger',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (BillingStatus | string $state): string => ucfirst($state?->value ?? $state)),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (WorkspaceStatus | string $state): string => match ($state?->value ?? $state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        'onboarding' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (WorkspaceStatus | string $state): string => ucfirst($state?->value ?? $state)),
                Tables\Columns\TextColumn::make('ownerMember.user.name')
                    ->label('Owner')
                    ->default('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('members_count')
                    ->label('Users')
                    ->counts('members')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => static::scopeQuery($query)->with('ownerMember.user'))
            ->filters([
                Tables\Filters\SelectFilter::make('workspace_type')
                    ->label('Workspace Type')
                    ->options(collect(WorkspaceType::cases())->mapWithKeys(fn ($t) => [$t->value => ucfirst(str_replace('_', ' ', $t->value))])),
                Tables\Filters\SelectFilter::make('plan')
                    ->label('Plan')
                    ->options(collect(Plan::cases())->mapWithKeys(fn ($p) => [$p->value => ucfirst(str_replace('_', ' ', $p->value))])),
                Tables\Filters\SelectFilter::make('billing_status')
                    ->label('Billing Status')
                    ->options(collect(BillingStatus::cases())->mapWithKeys(fn ($b) => [$b->value => ucfirst($b->value)])),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(WorkspaceStatus::cases())->mapWithKeys(fn ($s) => [$s->value => ucfirst($s->value)])),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->label('Open'),
                Actions\ActionGroup::make([
                    Actions\Action::make('reset_owner_password')
                        ->label('Reset Password')
                        ->icon('heroicon-o-key')
                        ->color('warning')
                        ->form([
                            Forms\Components\TextInput::make('new_password')
                                ->label('New Password')
                                ->password()
                                ->required()
                                ->minLength(8),
                        ])
                        ->action(function (Workspace $record, array $data) {
                            $ownerMember = $record->ownerMember;
                            if ($ownerMember && $ownerMember->user) {
                                $ownerMember->user->update([
                                    'password' => Hash::make($data['new_password']),
                                ]);
                            }
                        })
                        ->visible(fn (Workspace $record): bool => $record->ownerMember !== null),
                    Actions\Action::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Workspace $record): bool => $record->status === 'suspended' && Auth::user()?->isPlatformAdmin())
                        ->action(fn (Workspace $record) => $record->update(['status' => WorkspaceStatus::Active])),
                    Actions\Action::make('suspend')
                        ->label('Suspend')
                        ->icon('heroicon-o-minus-circle')
                        ->color('danger')
                        ->visible(fn (Workspace $record): bool => ! $record->isInternal() && $record->status !== 'suspended' && Auth::user()?->isPlatformAdmin())
                        ->action(fn (Workspace $record) => $record->update(['status' => WorkspaceStatus::Suspended])),
                ]),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            MembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkspaces::route('/'),
            'edit' => Pages\EditWorkspace::route('/{record}/edit'),
        ];
    }

    public static function scopeQuery(Builder $query): Builder
    {
        if (Auth::user()?->isPlatformAdmin()) {
            return $query;
        }

        return $query->whereHas('members', fn ($q) => $q->where('user_id', Auth::id()));
    }
}
