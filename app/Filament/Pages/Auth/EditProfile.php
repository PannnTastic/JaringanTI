<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\EditProfile as AuthEditProfile;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;


class EditProfile extends AuthEditProfile
{
   public function form(Form $form): Form
    {
        return $form
            ->schema(array_merge([
                FileUpload::make('photo')
                    ->image()
                    ->directory('avatars')
                    ->maxSize(2048)
                    ->label('Foto Profil'),
            ], parent::getFormSchema()));
    }
}  