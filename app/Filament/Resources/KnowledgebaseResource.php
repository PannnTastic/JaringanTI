<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KnowledgebaseResource\Pages;
use App\Filament\Resources\KnowledgebaseResource\RelationManagers;
use App\Models\Knowledgebase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class KnowledgebaseResource extends Resource
{
    protected static ?string $model = Knowledgebase::class;

    protected static ?string $navigationIcon = 'heroicon-s-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kb_name')
                    ->label('Nama Knowledgebase')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('kb_status')
                    ->label('Status Knowledgebase')
                    ->required(),
                Forms\Components\Select::make('field_id')
                    ->label('Kategori Knowledgebase')
                    ->required()
                    ->relationship('category', 'field_name'),
                Forms\Components\Select::make('user_id')
                    ->label('Nama User')
                    ->default(auth()->id())
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->relationship('user','name'),
                Forms\Components\RichEditor::make('kb_content')
                    ->label('Konten Knowledgebase')
                    ->required()
                    ->fileAttachmentsDirectory('uploads') // folder di storage/app/public/uploads
                    ->fileAttachmentsVisibility('public')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Penulis'),
                Tables\Columns\TextColumn::make('kb_name')
                    ->label('Nama Knowledgebase')
                    ->searchable(),
                Tables\Columns\IconColumn::make('kb_status')
                    ->label('Status Knowledgebase')
                    ->boolean(),
                // Tables\Columns\TextColumn::make('kb_content')
                //     ->label('Konten Knowledgebase')
                //     ->searchable()
                //     ->,
                Tables\Columns\TextColumn::make('category.field_name')
                    ->label('Nama Bidang')
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
                TrashedFilter::make()
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
            'index' => Pages\ListKnowledgebases::route('/'),
            'create' => Pages\CreateKnowledgebase::route('/create'),
            'edit' => Pages\EditKnowledgebase::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
    $query = parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);

    $user = Auth::user();

    // Jika bukan admin, filter hanya data milik user
    if ($user && !$user->hasRole('Administrator')) {
        $query->where('user_id', $user->getKey());
    }

    return $query;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('knowledgebases');
    }

    public static function canViewAny(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('knowledgebases');
    }
    
}
