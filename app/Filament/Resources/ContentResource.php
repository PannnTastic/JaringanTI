<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $navigationIcon = 'heroicon-s-photo';

    protected static ?string $navigationGroup = 'General';
    protected static ?string $navigationLabel = 'Konten';
    protected static ?string $modelLabel = 'Konten';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Jika bukan Administrator, hanya tampilkan content milik user yang login
        if (Auth::check() && Auth::user()->role && Auth::user()->role->role_name !== 'Administrator') {
            $query->where('user_id', Auth::id());
        }
        
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('content_title')
                    ->label('Judul Konten')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('Masukkan judul konten')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content_description')
                    ->label('Deskripsi Konten')
                    ->required()
                    ->placeholder('Masukkan deskripsi konten')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('content_photo')
                    ->label('Foto Konten')
                    ->required()
                    ->image()
                    ->disk('public')
                    ->directory('contents')
                    ->visibility('public')
                    ->columnSpanFull(),
                Forms\Components\Select::make('user_id')
                    ->label('Nama Pengunggah')
                    ->required()
                    ->relationship('user', 'name')
                    ->default(Auth::id())
                    ->disabled()
                    ->dehydrated()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content_title')
                    ->label('Judul Konten')
                    ->searchable(),
                Tables\Columns\TextColumn::make('content_description')
                    ->label('Deskripsi Konten')
                    ->limit(50),
                Tables\Columns\ImageColumn::make('content_photo')
                    ->label('Foto Konten')
                    ->disk('public')
                    ->height(50)
                    ->width(50)
                     // Menggunakan accessor dari model
                    ->checkFileExistence(false)
                    ->defaultImageUrl(url('/img/placeholder.png')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengunggah')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Filter Pengunggah')
                    ->relationship('user', 'name')
                    ->visible(fn () => Auth::check() && Auth::user()->role && Auth::user()->role->role_name === 'Administrator')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => 
                        Auth::check() && (
                            (Auth::user()->role && Auth::user()->role->role_name === 'Administrator') ||
                            Auth::id() === $record->user_id
                        )
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => 
                        Auth::check() && (
                            (Auth::user()->role && Auth::user()->role->role_name === 'Administrator') ||
                            Auth::id() === $record->user_id
                        )
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::check() && Auth::user()->role && Auth::user()->role->role_name === 'Administrator'),
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
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
