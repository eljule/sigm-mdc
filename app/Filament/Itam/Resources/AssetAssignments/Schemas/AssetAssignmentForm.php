<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetAssignments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssetAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('asset_id')
                    ->relationship('asset', 'computer_code')
                    ->label('Activo Tecnológico')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "[{$record->computer_code}] {$record->brand} {$record->model} ({$record->status})" . ($record->asset_code ? " (Patrimonial: {$record->asset_code})" : ''))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Empleado Asignado')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('office_id')
                    ->relationship('office', 'name')
                    ->label('Oficina / Dependencia')
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('assigned_at')
                    ->label('Fecha de Asignación')
                    ->default(now())
                    ->required(),
                DateTimePicker::make('returned_at')
                    ->label('Fecha de Devolución'),
                Textarea::make('notes')
                    ->label('Observaciones / Estado del equipo')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
