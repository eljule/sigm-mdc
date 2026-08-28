<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Personals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos de Identificación y Personales')
                    ->description('Ficha técnica de filiación del servidor público o empleado municipal.')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        Select::make('document_type_id')
                            ->relationship('documentType', 'name')
                            ->label('Tipo de Documento')
                            ->required()
                            ->default(1),
                        TextInput::make('document_number')
                            ->label('Número de Documento')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. 10783864'),
                        TextInput::make('full_name')
                            ->label('Nombres y Apellidos Completos')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej. JAIME HENRRY SANDOVAL NIEVES'),
                        TextInput::make('first_name')
                            ->label('Nombres')
                            ->maxLength(100)
                            ->placeholder('Ej. JAIME HENRRY'),
                        TextInput::make('paternal_surname')
                            ->label('Apellido Paterno')
                            ->maxLength(100)
                            ->placeholder('Ej. SANDOVAL'),
                        TextInput::make('maternal_surname')
                            ->label('Apellido Materno')
                            ->maxLength(100)
                            ->placeholder('Ej. NIEVES'),
                        Select::make('gender')
                            ->label('Sexo')
                            ->options([
                                'MASCULINO' => 'MASCULINO',
                                'FEMENINO' => 'FEMENINO',
                                'OTRO' => 'OTRO',
                            ]),
                        DatePicker::make('birth_date')
                            ->label('Fecha de Nacimiento'),
                        TextInput::make('phone')
                            ->label('Teléfono / Celular')
                            ->maxLength(30)
                            ->placeholder('Ej. 969123456'),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->maxLength(100)
                            ->placeholder('ejemplo@municastilla.gob.pe'),
                        TextInput::make('address')
                            ->label('Dirección de Domicilio')
                            ->maxLength(255)
                            ->columnSpan(2),
                    ]),

                Section::make('Adscripción Laboral y Estructura Orgánica')
                    ->description('Vínculo institucional, dependencia y cargo asignado.')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        Select::make('labor_condition_id')
                            ->relationship('laborCondition', 'name')
                            ->label('Condición Laboral')
                            ->required()
                            ->preload(),
                        Select::make('office_id')
                            ->relationship('office', 'name')
                            ->label('Oficina / Dependencia Organigrama')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('position')
                            ->label('Cargo / Puesto de Trabajo')
                            ->maxLength(150)
                            ->placeholder('Ej. ESPECIALISTA EN TECNOLOGÍAS DE LA INFORMACIÓN'),
                        DatePicker::make('hire_date')
                            ->label('Fecha de Ingreso / Contrato'),
                        Select::make('ubigeo_code')
                            ->relationship('ubigeo', 'district')
                            ->label('Ubigeo Residencia')
                            ->searchable()
                            ->preload(),
                        Toggle::make('is_active')
                            ->label('Personal Activo en Plantilla')
                            ->default(true),
                        Textarea::make('notes')
                            ->label('Observaciones / Legajo')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
