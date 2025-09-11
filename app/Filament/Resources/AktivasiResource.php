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
    protected static ?string $navigationGroup = 'Manajemen Gardu';

    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with([
            'user',           // Eager load user relationship
            'pops',
        ])
        ->withCount([
            'documents',      // Count documents relationship
            'penyerapan'      // Count penyerapan relationship
        ]);
        
        
        $user = Auth::user();
        if ($user) {
            $userRole = \App\Models\User::with('role')->find(Auth::id());
            if ($userRole && $userRole->role) {
                $roleName = strtolower($userRole->role->role_name);
                
                // Admin dan Administrator bisa melihat semua aktivasi (status 0 atau 1)
                if (in_array($roleName, ['admin', 'administrator'])) {
                    // Tampilkan semua dengan status 0 atau 1
                    $query->whereIn('substation_status', [0, 1]);
                }
                // Untuk role "aktivasi", tampilkan:
                // 1. Substation dengan status 0 yang belum ada user_id (untuk diambil/diaktivasi)
                // 2. Substation dengan status 0 atau 1 yang sudah diaktivasi oleh user ini (untuk diedit)
                elseif ($roleName === 'aktivasi') {
                    $query->where(function($q) {
                        $q->where(function($subQ) {
                            // Status 0 dan belum ada user_id
                            $subQ->where('substation_status', 0)
                                 ->whereNull('user_id');
                        })->orWhere(function($subQ) {
                            // Status 0 atau 1 dan sudah diaktivasi oleh user ini
                            $subQ->whereIn('substation_status', [0, 1])
                                 ->where('user_id', Auth::id());
                        });
                    });
                } else {
                    // Role lain: hanya tampilkan yang sudah diaktivasi dengan status 0 atau 1
                    $query->whereIn('substation_status', [0, 1]);
                          
                }
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
                    ->default('-')
                    ,
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

                TextInput::make('substation_material')
                    ->label('Material')
                    ->numeric()
                    ->prefix('Rp.')
                    ->live(onBlur:true),
                TextInput::make('substation_jasa')
                    ->label('Jasa')
                    ->numeric()
                    ->prefix('Rp.')
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $material = (float) $get('substation_material');
                        $jasa = (float) $get('substation_jasa');
                        $set('substation_rab', $material + $jasa);
                    })
                    ->live(onBlur:true),
                TextInput::make('substation_rab')
                     ->label('RAB')
                    ->numeric()
                    ->prefix('Rp.')
                    ->disabled()
                    ->dehydrated()
                    ,
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
               ->relationship('documents')
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
                            // ->acceptedFileTypes([
                            //     'application/pdf',
                            //     'image/png',
                            //     'image/jpeg',
                            //     'application/msword',
                            //     'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            //     // Excel
                            //     'application/vnd.ms-excel', // .xls
                            //     'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                            //     '.xls',
                            //     '.xlsx',
                            //     // Visio
                            //     'application/vnd.visio',
                            //     'application/vnd.ms-visio',                     // older/ambiguous
                            //     'application/vnd.ms-visio.drawing',             // vsdx common mapping
                            //     'application/vnd.ms-visio.viewer',    // fallback
                            //     '.vsd',
                            //     '.vsdx',
                            //     'application/zip',
                            //     'application/x-zip-compressed',
                            //     'application/octet-stream',
                            // ])
                            ->helperText('Format yang didukung: PDF, PNG, JPG, JPEG, DOC, DOCX, XLS, XLSX, VSD, VSDX.')
                            ->columnSpanFull()
                            // ->rules([
                            //     // extension-based check
                            //     'mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,vsd,vsdx,zip',
                            //     // explicit MIME types fallback (vsdx often detected as zip/octet)
                            //     // 'mimetypes:application/pdf,application/png,application/jpeg,application/jpg,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.visio,application/vnd.ms-visio,application/vnd.ms-visio.drawing,application/vnd.ms-visio.viewer,application/zip,application/x-zip-compressed,application/octet-stream',
                            // ])
                            ->maxSize(102400) // Maksimum 10MB
                            
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
                        return $record->substation_status ? 'Sudah Diaktivasi' : 'Belum Diaktivasi';
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

                // TextColumn::make('substation_fo')
                //     ->label('FO')
                //     ->searchable(),

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
