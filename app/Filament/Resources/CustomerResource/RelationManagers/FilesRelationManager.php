<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\FileRecord;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                FileUpload::make('file')
                    ->label('File')
                    ->disk('r2')
                    ->directory(fn () => 'workspaces/' . $this->ownerRecord->workspace_id . '/files/' . now()->format('Y/m'))
                    ->visibility('private')
                    ->preserveFilenames(false)
                    ->storeFileNamesIn('original_filename')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'image/png',
                        'image/jpeg',
                        'image/webp',
                    ])
                    ->maxSize(10240)
                    ->required()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $set('disk', 'r2');
                            $set('mime_type', $state->getMimeType());
                            $set('extension', $state->getExtension());
                            $set('size_bytes', $state->getSize());
                        }
                    }),
                Forms\Components\TextInput::make('name')
                    ->label('Display name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->rows(2)
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('extension')
                    ->label('Type')
                    ->badge(),
                Tables\Columns\TextColumn::make('size_bytes')
                    ->label('Size')
                    ->formatStateUsing(fn (?int $state): string => $state
                        ? ($state > 1048576
                            ? round($state / 1048576, 1) . ' MB'
                            : round($state / 1024, 1) . ' KB')
                        : '-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->headerActions([
                Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => [
                        ...$data,
                        'workspace_id' => $this->ownerRecord->workspace_id,
                        'uploaded_by' => Auth::id(),
                        'customer_id' => $this->ownerRecord->id,
                    ]),
            ])
            ->actions([
                Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (FileRecord $record) {
                        return Storage::disk($record->disk ?? 'r2')->download($record->path, $record->original_filename);
                    }),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
