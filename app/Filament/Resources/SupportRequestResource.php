<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportRequestResource\Pages;
use App\Models\SupportRequest;
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

class SupportRequestResource extends Resource
{
    protected static ?string $model = SupportRequest::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-lifebuoy';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Request Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'normal' => 'Normal',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ])
                            ->default('normal'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'in_progress' => 'In Progress',
                                'waiting_client' => 'Waiting Client',
                                'resolved' => 'Resolved',
                                'closed' => 'Closed',
                            ])
                            ->default('open'),
                    ])
                    ->columns(2),
                Schemas\Components\Section::make('Assignment')
                    ->schema([
                        Forms\Components\Select::make('assigned_to')
                            ->options(fn () => User::whereHas('workspaceMembers', fn ($q) => $q->where('workspace_id', WorkspaceContext::activeWorkspaceId())->where('status', 'active'))->orderBy('name')->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\DateTimePicker::make('due_date')
                            ->nullable(),
                    ])
                    ->columns(2),
                Schemas\Components\Section::make('Description & Notes')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'gray',
                        'normal' => 'primary',
                        'high' => 'warning',
                        'urgent' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'gray',
                        'in_progress' => 'info',
                        'waiting_client' => 'warning',
                        'resolved' => 'success',
                        'closed' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'normal' => 'Normal',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'waiting_client' => 'Waiting Client',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Assigned To')
                    ->options(fn () => User::whereHas('workspaceMembers', fn ($q) => $q->where('workspace_id', WorkspaceContext::activeWorkspaceId())->where('status', 'active'))->orderBy('name')->pluck('name', 'id')),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportRequests::route('/'),
            'create' => Pages\CreateSupportRequest::route('/create'),
            'edit' => Pages\EditSupportRequest::route('/{record}/edit'),
        ];
    }
}
