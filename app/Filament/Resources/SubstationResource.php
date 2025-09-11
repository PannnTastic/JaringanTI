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
    
    protected static ?string $navigationGroup = 'Manajemen Gardu';

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
                    ->maxLength(255),
                Forms\Components\TextInput::make('substation_motorized')
                    ->label('Motorized')
                    ->maxLength(255),
                Forms\Components\TextInput::make('substation_jarkom')
                    ->label('Jarkom')
                    ->maxLength(255),
                Forms\Components\TextInput::make('substation_priority')
                    ->label('Prioritas')
                    ->numeric(),
                Forms\Components\Select::make('pop_id')
                    ->label('POP')
                    ->relationship('pops', 'pop_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                // Forms\Components\Select::make('user_id')
                //     ->label('Created By')
                //     ->relationship('user', 'name')
                //     ->default(function () {
                //         return \Illuminate\Support\Facades\Auth::user()?->user_id;
                //     })
                //     ->disabled()
                //     ->dehydrated()
                //     ->nullable(),

                Forms\Components\Toggle::make('substation_aksesoris_WALLMOUNT_RAK')
                    ->label('WALLMOUNT RAK')
                    ->default(false),

                Forms\Components\Toggle::make('substation_aksesoris_UPS')
                    ->label('UPS')
                    ->default(false),

                Forms\Components\Toggle::make('substation_aksesoris_SWITCH')
                    ->label('SWITCH')
                    ->default(false),

                Forms\Components\Toggle::make('substation_aksesoris_ONT/ONU')
                    ->label('ONT/ONU')
                    ->default(false),

                Forms\Components\Toggle::make('substation_aksesoris_SERIAL_SERVER')
                    ->label('SERIAL SERVER')
                    ->default(false),

                Forms\Components\Toggle::make('substation_aksesoris_POWER')
                    ->label('ADA POWER')
                    ->default(false),

                    // Forms\Components\TextInput::make('substation_terdekat')
                    // ->label('substation Terdekat')
                    // ->default('-')
                    // ->maxLength(255),
                
                ]);
            }
            
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('substation_name')
                    ->label('Nama Substation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_feeder')
                    ->label('Feeder')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_motorized')
                    ->label('Motorized')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_jarkom')
                    ->label('Jarkom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_priority')
                    ->label('Priority')
                    ->searchable(),
                Tables\Columns\TextColumn::make('substation_terdekat')
                    ->label('Terdekat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pops.pop_name')
                    ->label('POP')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('documents_count')
                    ->label('Documents')
                    ->counts('documents')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('penyerapan_count')
                    ->label('Penyerapan')
                    ->counts('penyerapan')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('substation_aksesoris_WALLMOUNT_RAK')
                    ->label('WALLMOUNT_RAK')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('substation_aksesoris_UPS')
                    ->label('UPS')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('substation_aksesoris_SWITCH')
                    ->label('SWITCH')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('substation_aksesoris_ONT/ONU')
                    ->label('ONT/ONU')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('substation_aksesoris_SERIAL_SERVER')
                    ->label('SERIAL SERVER')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('substation_aksesoris_POWER')
                    ->label('ADA POWER')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),



                // Tables\Columns\TextColumn::make('substation_fo')
                //     ->searchable(),
                //     Tables\Columns\TextColumn::make('substation_kabel_fa')
                //     ->searchable(),
                
            ])
            ->filters([
                TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('pop_id')
                    ->label('POP')
                    ->relationship('pops', 'pop_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Created By')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
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
            'view_documents' => Pages\ViewSubstationDocuments::route('/{record}/documents'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with([
                'user',           // Eager load user relationship
                'pops',           // Eager load pops relationship
            ])
            ->withCount([
                'documents',      // Count documents relationship
                'penyerapan'      // Count penyerapan relationship
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