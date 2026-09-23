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
                \Filament\Forms\Components\Placeholder::make('assignment_status_banner')
                    ->label('')
                    ->hidden(fn ($record) => ! $record || ! $record->returned_at)
                    ->columnSpanFull()
                    ->content(fn ($record) => new \Illuminate\Support\HtmlString("
                        <div style='padding: 12px 16px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 8px; display: flex; align-items: center; gap: 10px;'>
                            <span style='font-size: 1.25rem;'>📦</span>
                            <div>
                                <strong style='color: #b45309;'>ASIGNACIÓN FINALIZADA / DEVUELTA:</strong>
                                <span style='color: inherit;'> Este registro es histórico. El equipo fue devuelto el <strong>" . ($record->returned_at ? $record->returned_at->format('d/m/Y H:i') : '') . "</strong> y actualmente se encuentra <strong>DISPONIBLE</strong> en el inventario.</span>
                            </div>
                        </div>
                    ")),
                Select::make('asset_id')
                    ->relationship(
                        'asset',
                        'computer_code',
                        fn ($query, $get, $record) => $query->where(function ($q) use ($record) {
                            // Solo mostrar activos disponibles
                            $q->whereIn('status', ['disponible', 'Disponible']);
                            // En edición: también incluir el activo actual del registro
                            if ($record && $record->asset_id) {
                                $q->orWhere('id', $record->asset_id);
                            }
                        })
                    )
                    ->label('Activo Tecnológico')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "[{$record->computer_code}] " . ($record->model?->brand?->name ?? '') . " " . ($record->model?->name ?? '') . " ({$record->status})" . ($record->asset_code ? " (Patrimonial: {$record->asset_code})" : ''))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Solo se muestran activos con estado Disponible.'),
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
