<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vínculo con Ficha de Personal (RRHH)')
                    ->description('Asocie una Ficha de Legajo de Personal para heredar automáticamente Nombres, DNI, Oficina y Condición Laboral.')
                    ->compact()
                    ->columns(2)
                    ->schema([
                        Select::make('personal_id')
                            ->relationship('personal', 'full_name')
                            ->label('Ficha de Personal Municipal')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $personal = \App\Models\Personal::find($state);
                                    if ($personal) {
                                        $set('name', $personal->full_name);
                                        $set('document_type_id', $personal->document_type_id);
                                        $set('document_number', $personal->document_number);
                                        $set('labor_condition_id', $personal->labor_condition_id);
                                        $set('office_id', $personal->office_id);
                                        if ($personal->email) {
                                            $set('email', $personal->email);
                                        }
                                    }
                                }
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Credenciales de Acceso al Sistema')
                    ->compact()
                    ->columns(2)
                    ->schema([
                        TextInput::make('username')
                            ->label('Usuario (Login)')
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Autogenerado si se deja vacío (ej. jsandoval)'),
                        TextInput::make('email')
                            ->label('Correo Electrónico de Notificaciones')
                            ->email()
                            ->nullable()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. jsandoval@municastilla.gob.pe'),
                        TextInput::make('password')
                            ->label('Contraseña de Acceso')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->placeholder(fn (string $operation): string => $operation === 'edit' ? 'Dejar en blanco para no cambiar' : '********'),
                        Toggle::make('is_active')
                            ->label('Usuario Habilitado en Sistema')
                            ->default(true),
                    ]),

                Section::make('Datos Complementarios de Usuario (Opcionales si está vinculado a RRHH)')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre Mostrado')
                            ->maxLength(150)
                            ->placeholder('Heredado de RRHH si está vinculado'),
                        Select::make('document_type_id')
                            ->relationship('documentType', 'name')
                            ->label('Tipo de Documento')
                            ->nullable()
                            ->preload(),
                        TextInput::make('document_number')
                            ->label('Nro. Documento')
                            ->maxLength(20)
                            ->placeholder('Heredado de RRHH'),
                        Select::make('labor_condition_id')
                            ->relationship('laborCondition', 'name')
                            ->label('Condición Laboral')
                            ->nullable()
                            ->preload(),
                        Select::make('office_id')
                            ->relationship('office', 'name')
                            ->label('Oficina / Dependencia')
                            ->nullable()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}
