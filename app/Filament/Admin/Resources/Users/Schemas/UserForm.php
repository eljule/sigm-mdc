<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre Completo')
                    ->required()
                    ->maxLength(150)
                    ->placeholder('Ej. Juan Pérez Prado'),
                TextInput::make('username')
                    ->label('Usuario (Login)')
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Autogenerado si se deja vacío (ej. jperez)'),
                TextInput::make('email')
                    ->label('Correo Institucional (Opcional)')
                    ->email()
                    ->nullable()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Ej. jperez@municipio.gob.pe'),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->placeholder(fn (string $operation): string => $operation === 'edit' ? 'Dejar en blanco para no cambiar' : '********'),
                Select::make('document_type_id')
                    ->relationship('documentType', 'name')
                    ->label('Tipo de Documento')
                    ->required()
                    ->preload(),
                TextInput::make('document_number')
                    ->label('Nro. Documento')
                    ->required()
                    ->maxLength(20)
                    ->placeholder('Ej. 45678912'),
                Select::make('labor_condition_id')
                    ->relationship('laborCondition', 'name')
                    ->label('Condición Laboral')
                    ->required()
                    ->preload(),
                Select::make('office_id')
                    ->relationship('office', 'name')
                    ->label('Oficina / Dependencia')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('personal_id')
                    ->label('ID Personal (RRHH - Futuro)')
                    ->numeric()
                    ->placeholder('Opcional'),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
