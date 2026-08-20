<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
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
                    ->label('Nombre del Permiso')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. editar-usuarios'),
                TextInput::make('guard_name')
                    ->label('Guard')
                    ->required()
                    ->maxLength(255)
                    ->default('web'),
            ]);
    }
}
