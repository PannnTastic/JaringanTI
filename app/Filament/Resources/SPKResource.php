<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SPKResource\Pages;
use App\Filament\Resources\SPKResource\RelationManagers;
use App\Models\Penyerapan;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Date;

class SPKResource extends Resource
{
    protected static ?string $model = Penyerapan::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $navigationGroup = 'Penyerapan';
    protected static ?string $navigationLabel = 'SPK';
    protected static ?string $modelLabel = 'Surat Perintah Kerja';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'spk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kontrak_penyerapan')
                    ->label('Kontrak Penyerapan')
                    ->options([
                        'Gardu' => 'Gardu',
                        'Presales' => 'Presales',
                        'Backbone' => 'Backbone',
                        'Migrasi' => 'Migrasi',
                        'SCADA' => 'SCADA',
                        'UPS/BATERAI' => 'UPS/BATERAI',
                        'Backbone & Lasmile' => 'Backbone & Lasmile'
                    ]),
                Select::make('status_transaksi')
                    ->label('Status Transaksi')
                    ->options([
                        'SPK' => 'SPK',
                        'PROSES SPK' => 'PROSES SPK',
                        'PROSES ADM' => 'PROSES ADM',
                        'SURVEY' => 'SURVEY',
                        'PROSES AMS' => 'PROSES AMS',
                        'PROSES SM INFRA' => 'PROSES SM INFRA',
                        'REVISI RAB' => 'REVISI RAB',
                        'PROSES ASMAN JARTI' => 'PROSES ASMAN JARTI',
                        'PROSES MAN INFRA' => 'PROSES MAN INFRA'
                    ]),
                TextInput::make('no_kontrak_spk')
                    ->label('No Kontrak SPK'),
                DatePicker::make('tanggal_kontrak_spk')
                    ->label('Tanggal Kontrak SPK'),
                Select::make('vendor_id')
                    ->label('Nama Vendor')
                    ->relationship('vendor', 'vendor_name')
                    ->searchable(),
                TextInput::make('estimasi_waktu')
                    ->label('Estimasi Waktu'),
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai'),
                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai'),
                TextInput::make('nilai_spk')
                    ->label('Nilai SPK')
                    ->numeric()
                    ->prefix('Rp.'),
                Select::make('bulan_spk')
                    ->label('Bulan SPK')
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
                TextInput::make('amandemen')
                    ->label('Amandemen')
                    ->numeric()
                    ->prefix('Rp.')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('substation.substation_name')
                    ->label('Nama Pekerjaan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kontrak_penyerapan')
                    ->label('Kontrak Penyerapan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_transaksi')
                    ->label('Status Transaksi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('no_kontrak_spk')
                    ->label('No Kontrak SPK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_kontrak_spk')
                    ->label('Tanggal Kontrak SPK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vendor_id')
                    ->label('Nama Vendor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('estimasi_waktu')
                    ->label('Estimasi Waktu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nilai_spk')
                    ->label('Nilai SPK')
                    ->prefix('Rp. ')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bulan_spk')
                    ->label('Bulan SPK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amandemen')
                    ->label('Amandemen')
                    ->prefix('Rp. ')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSPKS::route('/'),
            'edit' => Pages\EditSPK::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
