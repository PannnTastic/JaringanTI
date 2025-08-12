<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GarduResource\Pages;
use App\Filament\Resources\GarduResource\RelationManagers;
use App\Models\Gardu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GarduResource extends Resource
{
    protected static ?string $model = Gardu::class;

    protected static ?string $navigationIcon = 'heroicon-s-bolt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('gardu_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_feeder')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_motorized')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_jarkom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_proritas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_fo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_pop')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_terdekat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_kabel_fa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_kabel_fig')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_petik_core')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_pekerjaan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_rab')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gardu_perizinan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('gardus_status')
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gardu_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_feeder')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_motorized')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_jarkom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_proritas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_fo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_pop')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_terdekat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_kabel_fa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_kabel_fig')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_petik_core')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_rab')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gardu_perizinan')
                    ->searchable(),
                Tables\Columns\IconColumn::make('gardus_status')
                    ->boolean(),
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
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListGardus::route('/'),
            'create' => Pages\CreateGardu::route('/create'),
            'edit' => Pages\EditGardu::route('/{record}/edit'),
        ];
    }
}
