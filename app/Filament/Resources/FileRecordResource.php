<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileRecordResource\Pages;
use App\Models\FileRecord;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FileRecordResource extends Resource
{
    protected static ?string $model = FileRecord::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-folder';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('File Information')
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
                            ->required()
                            ->suffix('bytes'),
                    ])
                    ->columns(2),
                Schemas\Components\Section::make('Linked Entity')
                    ->schema([
                        Forms\Components\Select::make('related_entity_type')
                            ->options([
                                'customer' => 'Customer',
                                'lead' => 'Lead',
                                'task' => 'Task',
                                'quotation' => 'Quotation',
                            ]),
                        Forms\Components\TextInput::make('related_entity_id')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('file_size')
                    ->formatStateUsing(fn (int $state): string => $state > 1048576
                        ? round($state / 1048576, 1) . ' MB'
                        : round($state / 1024, 1) . ' KB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('related_entity_type')
                    ->label('Linked To')
                    ->badge(),
                Tables\Columns\TextColumn::make('uploaded_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([
                Tables\Filters\SelectFilter::make('related_entity_type')
                    ->options([
                        'customer' => 'Customer',
                        'lead' => 'Lead',
                        'task' => 'Task',
                        'quotation' => 'Quotation',
                    ]),
                Tables\Filters\SelectFilter::make('file_type'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
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
            'index' => Pages\ListFileRecords::route('/'),
            'create' => Pages\CreateFileRecord::route('/create'),
            'edit' => Pages\EditFileRecord::route('/{record}/edit'),
        ];
    }
}
