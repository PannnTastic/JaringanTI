<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()
                    ->label('Email or Username')
                    ->placeholder('Enter your email or username'),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    public function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label('Email or Username')
            ->placeholder('Enter your email or username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $login = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        // Determine if login is email or username
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return [
                'email' => $login,
                'password' => $password,
            ];
        } else {
            return [
                'username' => $login,
                'password' => $password,
            ];
        }
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
