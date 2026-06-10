<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileRecordResource\Pages;
use App\Models\FileRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FileRecordResource extends Resource
{
    protected static ?string $model = FileRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('file_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('file_url')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('file_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('file_size')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('related_entity_type')
                    ->options([
                        'customer' => 'Customer',
                        'task' => 'Task',
                        'quotation' => 'Quotation',
                    ]),
                Forms\Components\TextInput::make('related_entity_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('uploaded_by')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('file_size')
                    ->formatStateUsing(fn (int $state): string => $state > 1048576
                        ? round($state / 1048576, 1) . ' MB'
                        : round($state / 1024, 1) . ' KB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('related_entity_type')
                    ->label('Linked To'),
                Tables\Columns\TextColumn::make('uploaded_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
            'index' => Pages\ListFileRecords::route('/'),
            'create' => Pages\CreateFileRecord::route('/create'),
            'edit' => Pages\EditFileRecord::route('/{record}/edit'),
        ];
    }
}
