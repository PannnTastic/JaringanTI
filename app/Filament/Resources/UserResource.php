<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use function Livewire\Volt\dehydrate;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\ImageColumn;

use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\UserResource\Pages;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Forms\Components\Tabs\Tab;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Checkbox::make('change_password')
                ->label('Ganti Password')
                ->reactive()
                ->hidden(fn (string $context): bool => $context === 'create' )
                ->dehydrated(false),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->revealable()
                    ->maxLength(12)
                    ->placeholder(fn (string $context): string => 
                    $context === 'edit' ? 'Masukkan password baru' : 'Masukkan password'
                    )
                     ->hidden(fn (string $context, callable $get): bool => 
                    $context === 'edit' && !$get('change_password')
                    )
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null),
                    Select::make('user_gender')
                        ->label('Jenis Kelamin')
                        ->options([
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan',
                        ])
                        ->default('L')
                        ->required(),
                    Forms\Components\Textarea::make('user_address')
                        ->label('Alamat')
                        ->rows(3)
                        ->maxLength(500)
                        ->placeholder('Masukkan alamat lengkap'),
                    Forms\Components\DatePicker::make('user_birthday')
                        ->label('Tanggal Lahir')
                        ->placeholder('Pilih tanggal lahir')
                        ->displayFormat('d-m-Y')
                        ->maxDate(now())
                        ->required(),
                    Select::make('user_marital')
                        ->label('Status Pernikahan')
                        ->options([
                            'Menikah' => 'Menikah',
                            'Belum Menikah' => 'Belum Menikah',
                        ]),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->required(),
                Select::make('role_id')
                    ->label('ID Role')
                    ->relationship('role', 'role_name')
                    ->reactive()
                    ->required(),
                    FileUpload::make('user_photo')
                    ->label('Avatar')
                    ->image()
                    ->disk('public')
                    ->directory('avatars'),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
               
                
                Tables\Columns\TextColumn::make('role.role_name')
                    ->label('Role')
                    ->sortable(),
                
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
                Tables\Columns\ImageColumn::make('user_photo')
                    ->label('Avatar')
                    ->disk('public')
                    
                    
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
        return \App\Helpers\PermissionHelper::canAccessResource('users');
    }

    public static function canViewAny(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('users');
    }
}
