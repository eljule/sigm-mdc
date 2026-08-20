<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\View\View;

class Login extends BaseLogin
{


    /**
     * Mapea los datos del formulario a las credenciales de autenticación.
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    /**
     * Reemplaza el campo de email por el campo de username.
     */
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Usuario')
            ->required()
            ->autocomplete()
            ->autofocus();
    }
}
