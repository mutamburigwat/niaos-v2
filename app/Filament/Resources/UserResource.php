<?php

namespace App\Filament\Resources;

use App\Enums\WorkspaceRole;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Workspace;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Account Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8),
                    ])->columns(2),
                Schemas\Components\Section::make('Permissions')
                    ->schema([
                        Forms\Components\Toggle::make('is_platform_admin')
                            ->label('Platform Admin')
                            ->helperText('Grants access to all workspaces and platform-level management.')
                            ->visible(fn () => Auth::user()?->isPlatformAdmin()),
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
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_platform_admin')
                    ->label('Platform Admin')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('activeWorkspace.business_name')
                    ->label('Active Workspace')
                    ->default('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('is_platform_admin')
                    ->label('Platform Admins Only')
                    ->query(fn (Builder $query) => $query->where('is_platform_admin', true)),
                Tables\Filters\Filter::make('has_active_workspace')
                    ->label('Has Active Workspace')
                    ->query(fn (Builder $query) => $query->whereNotNull('active_workspace_id')),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\Action::make('reset_password')
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
                    ->action(function (User $record, array $data) {
                        $record->update(['password' => Hash::make($data['new_password'])]);
                    }),
                Actions\DeleteAction::make()
                    ->visible(fn (User $record): bool => Auth::user()?->isPlatformAdmin() && Auth::id() !== $record->id),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->isPlatformAdmin()),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->isPlatformAdmin() ?? false;
    }
}
