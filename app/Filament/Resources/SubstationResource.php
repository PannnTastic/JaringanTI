<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Substation;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationItem;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\SubstationResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubstationResource\RelationManagers;
use Dom\Text;
use Illuminate\Database\Eloquent\Factories\Relationship;

class SubstationResource extends Resource
{
    protected static ?string $model = Substation::class;

    protected static ?string $navigationIcon = 'heroicon-s-bolt';
    
    protected static ?string $navigationGroup = 'Substation Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('substation_name')
                    ->label('Nama Substation')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('substation_feeder')
                    ->label('Feeder')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('substation_motorized')
                    ->label('Motorized')
                    ->maxLength(255),
                Forms\Components\TextInput::make('substation_jarkom')
                    ->label('Jarkom')
                    ->maxLength(255),
                Forms\Components\TextInput::make('substation_priority')
                    ->label('Prioritas')
                    ->numeric()
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('substation_terdekat')
                    ->label('substation Terdekat')
                    ->default('-')
                    ->maxLength(255),
                    
                    
                    // Forms\Components\Select::make('substation_fo')
                    //     ->label('FO')
                    //     ->required()
                    //     ->options([
                    //         'Selesai' => 'Selesai',
                    //         'Survey' => 'Survey',
                    //         'Belum' => 'Belum',
                    //         'Progres' => 'Progres',
                    //         'Ada' => 'Ada'
                    //     ]),
                    // Forms\Components\TextInput::make('substation_cable_fa')
                    // ->label('Kabel FA')
                    // ->prefix('MTR')
                    // ->default(0)
                    // ->numeric(),
                    // Forms\Components\TextInput::make('substation_cable_fig')
                    // ->label('Kabel FIG')
                    // ->prefix('MTR')
                    // ->default(0)
                    // ->numeric(),
                    // Forms\Components\TextInput::make('substation_petik_core')
                    // ->label('Petik Core')
                    // ->required()
                    // ->maxLength(255),
                    // Forms\Components\TextInput::make('substation_work')
                    // ->label('Pekerjaan')
                    // ->required()
                    // ->maxLength(255),
                    // Forms\Components\TextInput::make('substation_rab')
                    // ->required()
                    // ->maxLength(255),

                    // Forms\Components\TextInput::make('substation_licensing')
                    // ->required()
                    // ->maxLength(255),

                    // Forms\Components\Select::make('pop_id')
                    // ->label('POP')
                    // ->required()
                    // ->relationship('pops','pop_name'),

                    // Forms\Components\Select::make('user_id')
                    // ->label('Nama User')
                    // ->default(auth()->id())
                    // ->disabled()
                    // ->dehydrated()
                    // ->relationship('users','name'),

                    // Forms\Components\Toggle::make('substation_status')
                    // ->required(),
                ]);
            }
            
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('substation_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_feeder')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_motorized')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_jarkom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_priority')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('substation_terdekat')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('substation_fo')
                //     ->searchable(),
                //     Tables\Columns\TextColumn::make('substation_kabel_fa')
                //     ->searchable(),
                //     Tables\Columns\TextColumn::make('substation_kabel_fig')
                //     ->searchable(),
                //     Tables\Columns\TextColumn::make('substation_petik_core')
                //     ->searchable(),
                //     Tables\Columns\TextColumn::make('substation_pekerjaan')
                //     ->searchable(),
                //     Tables\Columns\TextColumn::make('substation_rab')
                //     ->searchable(),
                //     Tables\Columns\TextColumn::make('substation_perizinan')
                //     ->searchable(),
                //     // menambahkan bulan sebagai kolom
                //     TextColumn::make('bulan')
                //     ->label('POP'),
                //     Tables\Columns\IconColumn::make('substation_status')
                //     ->boolean(),
                //     Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                //     Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                //     Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                //     Tables\Columns\TextColumn::make('users.name')
                //     ->sortable(),
                //     Tables\Columns\TextColumn::make('pops.pop_name')
                //         ->searchable(),
            ])
            ->filters([ 
                TrashedFilter::make()
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
            'index' => Pages\ListSubstation::route('/'),
            'create' => Pages\CreateSubstation::route('/create'),
            'edit' => Pages\EditSubstation::route('/{record}/edit'),
            'activation' => Pages\Activation::route('/activation'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    
// Tambahkan method ini di setiap Resource

public static function shouldRegisterNavigation(): bool
{
    return \App\Helpers\PermissionHelper::canAccessResource('substations');
}

public static function canViewAny(): bool
{
    return \App\Helpers\PermissionHelper::canAccessResource('substations');
}


}
