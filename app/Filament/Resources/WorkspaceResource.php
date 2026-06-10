<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkspaceResource\Pages;
use App\Models\Workspace;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class WorkspaceResource extends Resource
{
    protected static ?string $model = Workspace::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('business_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('industry')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->default('Zimbabwe')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->columnSpanFull(),
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
                Forms\Components\TextInput::make('subscription_plan')
                    ->default('free')
                    ->maxLength(255),
                Forms\Components\Select::make('onboarding_status')
                    ->options([
                        'pending_wizard' => 'Pending Wizard',
                        'completed' => 'Completed',
                    ])
                    ->default('pending_wizard'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('industry')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_currency'),
                Tables\Columns\TextColumn::make('subscription_plan')
                    ->badge(),
                Tables\Columns\TextColumn::make('onboarding_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending_wizard' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('onboarding_status')
                    ->options([
                        'pending_wizard' => 'Pending Wizard',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListWorkspaces::route('/'),
            'create' => Pages\CreateWorkspace::route('/create'),
            'edit' => Pages\EditWorkspace::route('/{record}/edit'),
        ];
    }
}
