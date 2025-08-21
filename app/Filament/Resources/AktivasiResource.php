<?php

namespace App\Filament\Resources;

use App\Models\Pop;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Substation;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AktivasiResource\Pages;
use Filament\Tables\Actions\DeleteBulkAction;


use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AktivasiResource\RelationManagers;
use Dom\Text;
use Filament\Forms\Components\FileUpload;

class AktivasiResource extends Resource
{
    protected static ?string $model = Substation::class;

    protected static ?string $navigationIcon = 'heroicon-s-check-circle';
    
    protected static ?string $navigationLabel = 'Aktivasi';
    
    protected static ?string $modelLabel = 'Aktivasi';
    
    protected static ?string $pluralModelLabel = 'Aktivasi';
    protected static ?string $navigationGroup = 'Substation Management';
    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = Auth::user();
        if ($user) {
            // Untuk role "aktivasi", tampilkan:
            // 1. Substation yang belum ada user_id (untuk diambil/diaktivasi)
            // 2. Substation yang sudah diaktivasi oleh user ini (untuk diedit)
            $userRole = \App\Models\User::with('role')->find(Auth::id());
            if ($userRole && $userRole->role && strtolower($userRole->role->role_name) === 'aktivasi') {
                $query->where(function($q) {
                    $q->whereNull('user_id')
                      ->orWhere('user_id', Auth::id());
                });
            } else {
                // Role lain: hanya tampilkan yang sudah diaktivasi
                $query->whereNotNull('user_id');
            }
        }
        
        return $query;
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('substation_name')
                    ->label('Nama Substation')
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Ini adalah data substation yang akan diaktivasi/diedit'),
                
                Select::make('pop_id')
                    ->label('POP / SUB POP')
                    ->options(Pop::pluck('pop_name', 'pop_id'))
                    ->searchable()
                    ,
                Select::make('substation_terdekat')
                    ->label('Gardu Terdekat')
                    ->options(Substation::pluck('substation_name', 'substation_name'))
                    ->searchable()
                    ->required(),
                TextInput::make('substation_feeder')
                    ->label('Feeder')
                    ->maxLength(255),
                    
                // TextInput::make('substation_fo')
                //     ->label('FO')
                //     ->maxLength(255),
                    
                    
                TextInput::make('substation_cable_fa')
                    ->label('Cable FA')
                    ->maxLength(255),
                    
                TextInput::make('substation_cable_fig')
                    ->label('Cable FIG')
                    ->maxLength(255),
                    
                TextInput::make('substation_petik_core')
                    ->label('Petik Core')
                    ->maxLength(255),
                    
                TextInput::make('substation_work')
                    ->label('Hari Kerja')
                    ->maxLength(255),
                    
                TextInput::make('substation_rab')
                    ->label('RAB')
                    ->numeric()
                    ->prefix('Rp.'),

                Select::make('substation_licensing')
                    ->label('Perizinan')
                    ->options([
                        'Kawasan' => 'Kawasan',
                        'RT/RW' => 'RT/RW',
                        'BP Batam' => 'BP Batam',
                    ])
                    ->multiple()
                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? implode(',', $state) : $state),

                    
                Toggle::make('substation_status')
                    ->label('Status Aktif')
                    ->default(false),

                Forms\Components\DatePicker::make('substation_periode')
                    ->label('Periode Pengajuan Awal')
                    ->default(now()->format('Y-m-d')) // default format tanggal lengkap
                    ->placeholder('Pilih tanggal')
                    ->displayFormat('d-m-Y'), // tampilkan hari-bulan-tahun 
                Select::make('user_id')
                    ->label('User (Terakhir Edit)')
                    ->options(\App\Models\User::pluck('name', 'user_id'))
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Menampilkan user yang terakhir mengedit data ini'),

                Forms\Components\Placeholder::make('edit_info')
                    ->label('Info Edit')
                    ->content(function ($record) {
                        if (!$record) {
                            $currentUserId = Auth::id();
                            $currentUserName = $currentUserId ? \App\Models\User::where('user_id', $currentUserId)->value('name') : null;
                            return 'Data baru - akan diedit oleh: ' . ($currentUserName ?? 'Unknown');
                        }
                        
                        $userName = \App\Models\User::where('user_id', $record->user_id)->value('name');
                        $lastUpdate = $record->updated_at ? $record->updated_at->format('d/m/Y H:i') : '-';
                        
                        return 'Terakhir diedit oleh: ' . ($userName ?? 'Unknown') . ' pada ' . $lastUpdate;
                    })
                    ->columnSpanFull(),

                 Repeater::make('documents')
                        ->relationship()
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

                    Forms\Components\RichEditor::make('substation_info')
                ->label('Substation Info')
                
                ->fileAttachmentsDirectory('uploads')
                ->fileAttachmentsVisibility('public')
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('activation_status')
                    ->label('Status Aktivasi')
                    ->getStateUsing(function ($record) {
                        return $record->user_id ? 'Sudah Diaktivasi' : 'Belum Diaktivasi';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sudah Diaktivasi' => 'success',
                        'Belum Diaktivasi' => 'warning',
                    }),
                    
                TextColumn::make('substation_name')
                    ->label('Nama Substation')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('substation_feeder')
                    ->label('Feeder')
                    ->searchable(),

                TextColumn::make('substation_fo')
                    ->label('FO')
                    ->searchable(),

                TextColumn:: make('substation_cable_fa')
                    ->label('Cable FA')
                    ->searchable(),
                TextColumn::make('substation_cable_fig')
                    ->label('Cable FIG')
                    ->searchable(),
                TextColumn::make('substation_petik_core')
                    ->label('Petik Core')
                    ->searchable(),
                TextColumn::make('substation_work')
                    ->label('Work Days')
                    ->searchable(),
                TextColumn::make('substation_rab')
                    ->label('RAB')
                    ->searchable()
                    ->prefix('Rp.'),

                IconColumn::make('substation_status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                TextColumn::make('substation_periode')
                    ->label('Periode')
                    ->badge()
                    ->date('F Y'),
                    
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                    
                TextColumn::make('pops.pop_name')
                    ->label('POP')
                    ->searchable(),
                    
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('substation_licensing')
                    ->label('Licensing')
                    ->formatStateUsing(function ($state) {
                        // Ubah array menjadi string tanpa kurung dan kutip
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }
                        if (is_string($state)) {
                            // Jika data tersimpan sebagai JSON string, decode dulu
                            $decoded = json_decode($state, true);
                            if (is_array($decoded)) {
                                return implode(', ', $decoded);
                            }
                        }
                        return $state ?: '-';
                    })
                    ->searchable(),
                    TextColumn::make('documents.doc_name')
                        ->label('Nama Dokumen')
                        ->searchable(),
                    TextColumn::make('documents.doc_file')
                        ->label('File Dokumen')
                        ->formatStateUsing(fn (string $state): string => basename($state))
                        ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('substation_status')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ]),
                    
                
                
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Aktivasi')
                    ->color('success')
                    ->icon('heroicon-s-check-circle'),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAktivasis::route('/'),
            'view' => Pages\ViewAktivasi::route('/{record}'),
            'edit' => Pages\EditAktivasi::route('/{record}/edit'),
            // Tidak ada create page - hanya bisa edit substation yang sudah ada
        ];
    }

    
    
    public static function shouldRegisterNavigation(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('substations');
    }

    public static function canViewAny(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('substations');
    }

    
}
