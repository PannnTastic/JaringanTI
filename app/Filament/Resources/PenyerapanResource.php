<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenyerapanResource\Pages;
use App\Filament\Resources\PenyerapanResource\RelationManagers;
use App\Models\Penyerapan;
use App\Models\Substation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenyerapanResource extends Resource
{
    protected static ?string $model = Penyerapan::class;

    protected static ?string $navigationIcon = 'heroicon-s-circle-stack';

    protected static ?string $navigationGroup = 'Penyerapan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_surat_survey_penyerapan')
                    ->label('No Surat Survey Penyerapan')
                    ->numeric(),
                Forms\Components\Select::make('substation_id')
                    ->label('Nama Pekerjaan')
                    ->relationship('substation', 'substation_name')
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                                $gardu = Substation::find($state);
                                if ($gardu) {
                                    $set('rab_penyerapan', $gardu->substation_rab);
                                }
                            }),
                Forms\Components\TextInput::make('lokasi_pekerjaan')
                    ->label('Lokasi Pekerjaan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rab_penyerapan')
                    ->label('RAB Penyerapan')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->prefix('Rp.')
            ]);
    } 

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_surat_survey_penyerapan')
                    ->label('No Surat Survey Penyerapan'),
                Tables\Columns\TextColumn::make('substation.substation_name')
                    ->label('Nama Pekerjaan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi_pekerjaan')
                    ->label('Lokasi Pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rab_penyerapan')
                    ->label('RAB')
                    ->prefix('Rp.'),
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
            'index' => Pages\ListPenyerapans::route('/'),
            'create' => Pages\CreatePenyerapan::route('/create'),
            'edit' => Pages\EditPenyerapan::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('penyerapans');
    }

    public static function canViewAny(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('penyerapans');
    }
}
