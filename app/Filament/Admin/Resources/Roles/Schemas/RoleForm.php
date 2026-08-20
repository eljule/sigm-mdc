<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Roles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subsystem_id')
                    ->relationship('subsystem', 'name')
                    ->label('Subsistema')
                    ->required()
                    ->preload(),
                TextInput::make('name')
                    ->label('Nombre del Rol')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. administrador'),
                TextInput::make('guard_name')
                    ->label('Guard')
                    ->required()
                    ->maxLength(255)
                    ->default('web'),
            ]);
    }
}
