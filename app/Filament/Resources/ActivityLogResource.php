<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clock';
    protected static string | \UnitEnum | null $navigationGroup = 'Records';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Activity')
                    ->html()
                    ->getStateUsing(function (ActivityLog $log): string {
                        $dot = match ($log->action) {
                            'created' => '<span style="color: #16a34a;">●</span>',
                            'updated' => '<span style="color: #2563eb;">●</span>',
                            'deleted' => '<span style="color: #dc2626;">●</span>',
                            default   => '<span style="color: #6b7280;">●</span>',
                        };
                        return $dot . ' ' . e($log->details ?? "{$log->actor_name} {$log->action} {$log->entity_type}");
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('details', 'like', "%{$search}%")
                            ->orWhere('actor_name', 'like', "%{$search}%")
                            ->orWhere('entity_type', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('entity_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'customer' => 'success',
                        'lead' => 'warning',
                        'task' => 'info',
                        'quotation' => 'primary',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('actor_name')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('When')
                    ->since()
                    ->sortable(),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([
                Tables\Filters\SelectFilter::make('entity_type')
                    ->options([
                        'customer' => 'Customer',
                        'lead' => 'Lead',
                        'task' => 'Task',
                        'quotation' => 'Quotation',
                        'conversation' => 'Conversation',
                        'setting' => 'Setting',
                        'ai' => 'AI',
                        'general' => 'General',
                    ]),
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
