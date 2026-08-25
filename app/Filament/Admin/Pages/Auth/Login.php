<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use App\Models\User;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    /**
     * Muestra la notificación de registro exitoso si fue redirigido desde la solicitud de cuenta.
     */
    public function mount(): void
    {
        parent::mount();

        if ($message = session('registration_success')) {
            Notification::make()
                ->title('¡Solicitud de Registro Enviada!')
                ->body($message)
                ->warning()
                ->persistent()
                ->send();
        }
    }

    /**
     * Mapea los datos del formulario a las credenciales de autenticación.
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username'  => $data['username'],
            'password'  => $data['password'],
            'is_active' => true, // Exige que la cuenta esté activa y aprobada por ODT
        ];
    }

    /**
     * Personaliza el subtítulo del login con un enlace directo y visible de registro.
     */
    public function getSubheading(): ?Htmlable
    {
        if (filament()->hasRegistration()) {
            return new HtmlString('¿No tienes una cuenta? <a href="' . filament()->getRegistrationUrl() . '" style="color: #22c55e; font-weight: 700; text-decoration: underline;">Solicitar Registro de Usuario</a>');
        }

        return null;
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

    /**
     * Personaliza el mensaje de error para usuarios inactivos / pendientes de aprobación por ODT.
     */
    protected function throwFailureValidationException(): never
    {
        $username = $this->form->getState()['username'] ?? null;
        $user = $username ? User::where('username', $username)->first() : null;

        if ($user && ! $user->is_active) {
            throw ValidationException::withMessages([
                'data.username' => 'Tu cuenta está registrada pero aún se encuentra PENDIENTE DE APROBACIÓN por la Oficina de Desarrollo Tecnológico (ODT).',
            ]);
        }

        parent::throwFailureValidationException();
    }
}
