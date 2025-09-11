<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Document;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use Filament\Tables\Filters\SelectFilter;

use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\DocumentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DocumentResource\RelationManagers;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-check';
    protected static ?string $navigationGroup = 'General';
    protected static ?string $navigationLabel = 'Dokumen';
    protected static ?string $modelLabel = 'Dokumen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('doc_name')
                    ->label('Nama Dokumen')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('doc_status')
                    ->label('Status Dokumen')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Nama User')
                    ->default(auth()->id())
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->relationship('user','name'),
                    
                Forms\Components\FileUpload::make('doc_file')
                    ->label('File Dokumen')
                    ->directory('documents')
                    ->preserveFilenames()
                   ->previewable()
                    ->acceptedFileTypes([
                                'application/pdf',
                                'image/png',
                                'image/jpeg',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                // Excel
                                'application/vnd.ms-excel', // .xls
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                                '.xls',
                                '.xlsx',
                                // Visio
                                'application/vnd.visio',                     // older/ambiguous
                                'application/vnd.ms-visio',                     // older/ambiguous
                                'application/vnd.ms-visio.drawing',             // vsdx common mapping
                                'application/vnd.ms-visio.viewer',    // fallback
                                '.vsd',
                                '.vsdx',
                                'application/zip',
                                'application/octet-stream',
                            ])
                            ->helperText('Format yang didukung: PDF, PNG, JPG, JPEG, DOC, DOCX, XLS, XLSX, VSD, VSDX.')
                            ->columnSpanFull()
                            ->rules(['mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,vsd,vsdx,zip' ])
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doc_name')
                    ->label('Nama Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doc_file')
                    ->label('File Dokumen')
                    ->formatStateUsing(fn (string $state): string => basename($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('doc_status')
                    ->label('Status Dokumen')
                    ->badge(fn ($record) => $record->doc_status ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn ($record) => $record->doc_status ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama User')
                    ->sortable(),
                TextColumn::make('substations.substation_name')
                    ->label('Substation')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('role')
                    ->label('Role User')
                    ->options(
                        Role::pluck('role_name', 'role_id')->toArray()
                    )
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                fn (Builder $query, $value): Builder => $query->whereHas('user.role', function ($q) use ($value) {
                                    $q->where('role_id', $value);
                                }),
                            );
                    }),
            ])

            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Document $record) {
                        if (!$record->doc_file) {
                            \Filament\Notifications\Notification::make()
                                ->title('File tidak ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        $filePath = storage_path('app/public/' . $record->doc_file);
                        
                        if (!file_exists($filePath)) {
                            \Filament\Notifications\Notification::make()
                                ->title('File tidak ada di server')
                                ->danger()
                                ->send();
                            return;
                        }

                        return response()->download($filePath, $record->doc_name . '.' . pathinfo($record->doc_file, PATHINFO_EXTENSION));
                    })
                    ->visible(fn (Document $record) => !empty($record->doc_file)),

                // Action View File
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Document $record) => $record->doc_file ? Storage::url($record->doc_file) : null)
                    ->openUrlInNewTab()
                    ->visible(fn (Document $record) => !empty($record->doc_file)),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

        public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('documents');
    }

    public static function canViewAny(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('documents');
    }
}
