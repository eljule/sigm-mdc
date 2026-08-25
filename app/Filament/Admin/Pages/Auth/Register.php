<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use App\Models\DocumentType;
use App\Models\LaborCondition;
use App\Models\Office;
use App\Models\Role;
use App\Models\User;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Register extends BaseRegister
{
    protected string $view = 'filament.admin.pages.auth.register';

    public ?string $registeredUsername = null;

    /**
     * Título de la página de registro.
     */
    public function getTitle(): string
    {
        return 'Solicitud de Registro de Usuario';
    }

    public function getHeading(): string
    {
        if ($this->registeredUsername) {
            return '';
        }

        return 'Solicitar Cuenta de Usuario';
    }

    public function getSubheading(): ?string
    {
        if ($this->registeredUsername) {
            return null;
        }

        return 'Completa tus datos institucionales. Tu usuario de acceso será autogenerado y tu cuenta pasará a revisión por la Oficina de Desarrollo Tecnológico (ODT).';
    }

    /**
     * Define el formulario de registro institucional.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre Completo (Nombres y Apellidos)')
                    ->required()
                    ->maxLength(150)
                    ->placeholder('Ej. Juan Carlos Pérez Prado'),
                Select::make('document_type_id')
                    ->label('Tipo de Documento')
                    ->options(DocumentType::pluck('name', 'id'))
                    ->required()
                    ->preload(),
                TextInput::make('document_number')
                    ->label('Nro. Documento')
                    ->required()
                    ->maxLength(20)
                    ->placeholder('Ej. 45678912'),
                Select::make('office_id')
                    ->label('Oficina / Dependencia Municipal')
                    ->options(Office::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('labor_condition_id')
                    ->label('Condición Laboral')
                    ->options(LaborCondition::pluck('name', 'id'))
                    ->required()
                    ->preload(),
                TextInput::make('email')
                    ->label('Correo Institucional / Personal (Opcional)')
                    ->email()
                    ->nullable()
                    ->maxLength(100)
                    ->placeholder('Ej. jperez@municipio.gob.pe'),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->placeholder('Mínimo 8 caracteres'),
            ]);
    }

    /**
     * Sobrescribir register() para mostrar la pantalla de confirmación sin redirigir inmediatamente.
     */
    public function register(): ?\Filament\Auth\Http\Responses\Contracts\RegistrationResponse
    {
        $data = $this->form->getState();

        $user = $this->handleRegistration($data);

        $this->registeredUsername = $user->username;

        session()->flash('registration_success', "Tu usuario asignado para ingresar al sistema es: '{$user->username}'. Tu cuenta se encuentra en revisión y aprobación por la Oficina de Desarrollo Tecnológico (ODT).");

        return null; // Evita el redirect automático de Filament para renderizar la pantalla de confirmación
    }

    /**
     * Registra el usuario con username autogenerado y estado inactivo (is_active = false) a la espera de aprobación de ODT.
     */
    protected function handleRegistration(array $data): User
    {
        $user = new User([
            'name'               => $data['name'],
            'document_type_id'   => $data['document_type_id'],
            'document_number'    => trim($data['document_number']),
            'office_id'          => $data['office_id'],
            'labor_condition_id' => $data['labor_condition_id'],
            'email'              => ! empty($data['email']) ? Str::lower(trim($data['email'])) : null,
            'password'           => Hash::make($data['password']),
            'is_active'          => false, // Inactivo a la espera de aprobación por ODT
        ]);

        // Autogenerar username con regla: 1ra letra nombre + 1er apellido [+ 1ra letra 2do apellido si hay coincidencia]
        $user->username = $user->generateUniqueUsername();
        $user->save();

        // Asignar rol por defecto 'Usuario Reportante' en Helpdesk (Subsystem ID 4)
        $role = Role::where('subsystem_id', 4)->where('name', 'Usuario Reportante')->first();
        if ($role) {
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(4);
            $user->assignRole($role);
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(null);
        }

        return $user;
    }
}
