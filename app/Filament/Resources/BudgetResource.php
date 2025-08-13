<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Budget;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BudgetResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $navigationIcon = 'heroicon-s-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('budget_name')
                    ->label('Nama Anggaran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('budget_wbs')
                    ->label('WBS')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('budget_nilai')
                    ->label('Nilai Anggaran')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('budget_status')
                    ->label('Status Anggaran')
                    ->required()
                    ->options([
                        'terkontrak' => 'Terkontrak',
                        'terbayar' => 'Terbayar',
                        'progres' => 'Progres',
                        'rencana' => 'Rencana'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('budget_name')
                    ->label('Nama Anggaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget_wbs')
                    ->label('WBS')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget_nilai')
                    ->label('Nilai Anggaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget_status')
                    ->label('Status Anggaran')
                    ->badge(fn ($record) => match ($record->budget_status) {
                        'terkontrak' => 'success',
                        'terbayar' => 'info',
                        'progres' => 'warning',
                        'rencana' => 'secondary',
                    })
                    ->searchable(),
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
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/create'),
            'edit' => Pages\EditBudget::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function shouldRegisterNavigation(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('budgets');
    }

    public static function canViewAny(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('budgets');
    }

    
}
