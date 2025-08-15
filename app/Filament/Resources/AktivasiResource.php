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
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AktivasiResource\Pages;

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
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('substation_name')
                    ->label('Pilih Substation')
                    ->options(Substation::pluck('substation_name', 'substation_name'))
                    ->searchable()
                    ->required()
                    ->placeholder('Pilih substation yang sudah ada'),
                
                Select::make('pop_id')
                    ->label('POP')
                    ->options(Pop::pluck('pop_name', 'pop_id'))
                    ->searchable()
                    ->required(),
                Select::make('substation_terdekat')
                    ->label('Terdekat')
                    ->options(Substation::pluck('substation_terdekat', 'substation_terdekat'))
                    ->searchable()
                    ->required(),
                TextInput::make('substation_feeder')
                    ->label('Feeder')
                    ->maxLength(255),
                    
                TextInput::make('substation_fo')
                    ->label('FO')
                    ->maxLength(255),
                    
                    
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


                    Repeater::make('documents')
                        ->relationship()
                        ->schema([
                        TextInput::make('doc_name')
                            ->label('Nama Dokumen'),
                        Select::make('user_id')
                            ->label('Nama User')
                            ->default(auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->relationship('users','name'),
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
                            ->helperText('Format yang didukung: PDF, PNG, JPG, JPEG, DOC, DOCX. Maksimal 2MB'),
                    ]),

                    
                Toggle::make('substation_status')
                    ->label('Status Aktif')
                    ->default(true),
                    
                Select::make('bulan')
                    ->label('Bulan')
                    ->options([
                        'Januari' => 'Januari',
                        'Februari' => 'Februari', 
                        'Maret' => 'Maret',
                        'April' => 'April',
                        'Mei' => 'Mei',
                        'Juni' => 'Juni',
                        'Juli' => 'Juli',
                        'Agustus' => 'Agustus',
                        'September' => 'September',
                        'Oktober' => 'Oktober',
                        'November' => 'November',
                        'Desember' => 'Desember',
                    ])
                    ->searchable(),
                    
                Select::make('user_id')
                    ->label('User')
                    ->default(auth()->id())
                    ->relationship('users', 'name')
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(function (Builder $query){
            $user = auth()->user();
            if ($user && $user->hasRole('Aktivasi')) {
                $query->where('user_id', $user->user_id);
            }
            return $query;
        })
            ->columns([
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
                    ->label('Hari Kerja')
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
                    
                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->badge(),
                    
                TextColumn::make('users.name')
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
                    
                Tables\Filters\SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options([
                        'Januari' => 'Januari',
                        'Februari' => 'Februari', 
                        'Maret' => 'Maret',
                        'April' => 'April',
                        'Mei' => 'Mei',
                        'Juni' => 'Juni',
                        'Juli' => 'Juli',
                        'Agustus' => 'Agustus',
                        'September' => 'September',
                        'Oktober' => 'Oktober',
                        'November' => 'November',
                        'Desember' => 'Desember',
                    ]),
                
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'create' => Pages\CreateAktivasi::route('/create'),
            'view' => Pages\ViewAktivasi::route('/{record}'),
            'edit' => Pages\EditAktivasi::route('/{record}/edit'),
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
