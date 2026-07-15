<?php

declare(strict_types=1);

namespace App\Filament\Helpdesk\Resources\TicketCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TicketCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre de Categoría')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Ej. Red y conectividad'),
                TextInput::make('sla_hours')
                    ->label('Horas límite de atención (SLA)')
                    ->numeric()
                    ->required()
                    ->placeholder('Ej. 4'),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(500)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
