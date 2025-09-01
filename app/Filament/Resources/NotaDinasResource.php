<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotaDinasResource\Pages;
use App\Filament\Resources\NotaDinasResource\RelationManagers;
use App\Models\Penyerapan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotaDinasResource extends Resource
{
    protected static ?string $model = Penyerapan::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';
    protected static ?string $navigationGroup = 'Penyerapan';
    protected static ?string $navigationLabel = 'Nota Dinas';
    protected static ?string $modelLabel = 'Nota Dinas';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'nota-dinas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal_nota_dinas')
                    ->label('Tanggal Nota Dinas')
                    ->required(),
                Forms\Components\TextInput::make('diproses')
                    ->label('Diproses')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_penyerapan')
                    ->label('ID Penyerapan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_nota_dinas')
                    ->label('Tanggal Nota Dinas')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('diproses')
                    ->label('Diproses')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListNotaDinas::route('/'),
            'edit' => Pages\EditNotaDinas::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('nota-dinas');
    }

    public static function canViewAny(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('nota-dinas');
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
