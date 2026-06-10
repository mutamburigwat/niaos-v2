<?php

namespace App\Filament\Resources\WorkspaceResource\RelationManagers;

use App\Enums\WorkspaceRole;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('role')
                    ->options(collect(WorkspaceRole::cases())->mapWithKeys(fn ($r) => [$r->value => ucfirst($r->value)]))
                    ->default('viewer')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->default('active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'owner' => 'primary',
                        'admin' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('joined_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(collect(WorkspaceRole::cases())->mapWithKeys(fn ($r) => [$r->value => ucfirst($r->value)])),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->visible(fn () => Auth::user()?->isPlatformAdmin()),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->visible(fn () => Auth::user()?->isPlatformAdmin()),
                Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()?->isPlatformAdmin()),
            ]);
    }
}
