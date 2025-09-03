<?php

namespace App\Filament\Resources;

use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use App\Models\Document;
use Filament\Forms\Form;
use App\Models\Penyerapan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use \ZipArchive;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;

class PembayaranResource extends Resource
{
    protected static ?string $model = Penyerapan::class;

    protected static ?string $navigationIcon = 'heroicon-s-currency-dollar';
    protected static ?string $navigationGroup = 'Penyerapan';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('tahap1')
                    ->label('Tahap 1')
                    ->numeric()
                    ->prefix('Rp.'),
                TextInput::make('tahap2')
                    ->label('Tahap 2')
                    ->numeric()
                    ->prefix('Rp.'),
                TextInput::make('keterangan_tahap1')
                    ->label('Keterangan Tahap 1'),
                TextInput::make('keterangan_tahap2')
                    ->label('Keterangan Tahap 2'),
                Repeater::make('documents')
                    ->relationship('documents')
                    ->label(' BAPP \ BASTP')
                    ->schema([
                        TextInput::make('doc_name')
                            ->label('Nama Dokumen'),
                        Select::make('user_id')
                            ->label('Nama User')
                            ->default(Auth::id())
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->options(\App\Models\User::pluck('name', 'user_id')),
                        FileUpload::make('doc_file')
                            ->label('File Dokumen')
                            ->directory('documents')
                            ->preserveFilenames()
                            ->previewable()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/png',
                                'image/jpeg',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/msword',
                            ])
                            ->helperText('Format yang didukung: PDF, PNG, JPG, JPEG, DOC, DOCX.'),
                    ]),
                DatePicker::make('tanggal_bapp')
                    ->label('Tanggal BAPP'),
                DatePicker::make('tanggal_bastp')
                    ->label('Tanggal BASTP'),
                TextInput::make('keterangan')
                    ->label('Keterangan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('substation.substation_name')
                    ->label('Nama Pekerjaan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tahap1')
                    ->label('Tahap 1')
                    ->prefix('Rp. '),
                TextColumn::make('tahap2')
                    ->label('Tahap 2')
                    ->prefix('Rp. '),
                TextColumn::make('keterangan_tahap1')
                    ->label('Keterangan Tahap 1'),
                TextColumn::make('keterangan_tahap2')
                    ->label('Keterangan Tahap 2'),
                TextColumn::make('documents.doc_name')
                    ->label('Nama Dokumen'),
                TextColumn::make('documents.doc_file')
                    ->label('File Dokumen')
                    ->formatStateUsing(fn (string $state): string => basename($state))
                    ->searchable(),
                TextColumn::make('tanggal_bapp')
                    ->label('Tanggal BAPP'),
                TextColumn::make('tanggal_bastp')
                    ->label('Tanggal BASTP'),
                TextColumn::make('keterangan')
                    ->label('Keterangan'),
            ])
            ->filters([
                //
            ])
                        ->actions([
                Tables\Actions\EditAction::make(),

                // Tables\Actions\Action::make('download')
                //     ->label('Download Semua')
                //     ->icon('heroicon-o-arrow-down-tray')
                //     ->color('success')
                //     ->action(function ($record) {
                //         $documents = $record->documents()->whereNotNull('doc_file')->get();
                //         if ($documents->isEmpty()) {
                //             \Filament\Notifications\Notification::make()
                //                 ->title('Tidak ada file untuk diunduh')
                //                 ->danger()
                //                 ->send();
                //             return;
                //         }

                //         $tempDir = storage_path('app/temp');
                //         if (! is_dir($tempDir)) {
                //             mkdir($tempDir, 0755, true);
                //         }

                //         $zipPath = $tempDir . '/pembayaran-' . $record->id_penyerapan . '-' . time() . '.zip';
                //         $zip = new ZipArchive();
                //         if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
                //             \Filament\Notifications\Notification::make()
                //                 ->title('Gagal membuat arsip')
                //                 ->danger()
                //                 ->send();
                //             return;
                //         }

                //         foreach ($documents as $doc) {
                //             $file = storage_path('app/public/' . $doc->doc_file);
                //             if (file_exists($file)) {
                //                 $zip->addFile($file, basename($doc->doc_file));
                //             }
                //         }

                //         $zip->close();

                //         return response()->download($zipPath)->deleteFileAfterSend(true);
                //     })
                //     ->visible(function ($record) {
                //         return (bool) $record->documents()->whereNotNull('doc_file')->exists();
                //     }),

                // Tables\Actions\Action::make('view')
                //     ->label('Lihat Semua')
                //     ->icon('heroicon-o-eye')
                //     ->color('info')
                //     ->url(fn ($record) => route('pembayaran.documents', ['penyerapan' => $record->id_penyerapan]))
                //     ->openUrlInNewTab()
                //     ->visible(fn ($record) => (bool) $record->documents()->whereNotNull('doc_file')->exists()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPembayarans::route('/'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
