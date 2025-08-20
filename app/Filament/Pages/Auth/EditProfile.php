<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Forms\Components\TextInput;
use Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Auth\EditProfile as AuthEditProfile;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use function Livewire\Volt\dehydrate;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\ImageColumn;


use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\UserResource\Pages;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Forms\Components\Tabs\Tab;


class EditProfile extends AuthEditProfile
{
   public function form(Form $form): Form
    {
        return $form
            ->schema(array_merge([
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
                FileUpload::make('user_photo')
                    ->image()
                    ->directory('avatars')
                    ->maxSize(2048)
                    ->label('Foto Profil'),
            ], parent::getFormSchema()));
    }
}  