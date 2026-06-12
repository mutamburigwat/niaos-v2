<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileRecordResource\Pages;
use App\Models\Customer;
use App\Models\FileRecord;
use App\Services\WorkspaceContext;
use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileRecordResource extends Resource
{
    protected static ?string $model = FileRecord::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-folder';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Upload File')
                    ->schema([
                        FileUpload::make('file')
                            ->label('File')
                            ->disk('r2')
                            ->directory(fn () => 'workspaces/' . WorkspaceContext::activeWorkspaceId() . '/files/' . now()->format('Y/m'))
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
                            ->afterStateUpdated(function ($state, $set, $get) {
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
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer (optional)')
                            ->options(fn () => Customer::query()->currentWorkspace()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                        Forms\Components\Textarea::make('notes')
                            ->rows(2)
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('original_filename')
                    ->label('Original file')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('extension')
                    ->label('Type')
                    ->badge(),
                Tables\Columns\TextColumn::make('size_bytes')
                    ->label('Size')
                    ->formatStateUsing(fn (?int $state): string => $state
                        ? ($state > 1048576
                            ? round($state / 1048576, 1) . ' MB'
                            : round($state / 1024, 1) . ' KB')
                        : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('uploaded_by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->currentWorkspace())
            ->filters([
                Tables\Filters\SelectFilter::make('extension')
                    ->label('File type')
                    ->options([
                        'pdf' => 'PDF',
                        'doc' => 'DOC',
                        'docx' => 'DOCX',
                        'xls' => 'XLS',
                        'xlsx' => 'XLSX',
                        'png' => 'PNG',
                        'jpg' => 'JPG',
                        'jpeg' => 'JPEG',
                        'webp' => 'WebP',
                    ]),
            ])
            ->actions([
                Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (FileRecord $record) {
                        return Storage::disk($record->disk ?? 'r2')->download($record->path, $record->original_filename);
                    }),
                Actions\Action::make('preview')
                    ->label('Open')
                    ->icon('heroicon-o-eye')
                    ->url(fn (FileRecord $record) => Storage::disk($record->disk ?? 'r2')->url($record->path))
                    ->openUrlInNewTab()
                    ->visible(fn (FileRecord $record) => $record->isImage()),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
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
            'index' => Pages\ListFileRecords::route('/'),
            'create' => Pages\CreateFileRecord::route('/create'),
            'edit' => Pages\EditFileRecord::route('/{record}/edit'),
        ];
    }
}
