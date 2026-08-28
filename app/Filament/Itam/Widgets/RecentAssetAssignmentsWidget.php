<?php

namespace App\Filament\Itam\Widgets;

use App\Models\AssetAssignment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentAssetAssignmentsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AssetAssignment::query()->latest('assigned_at')->limit(5)
            )
            ->heading('Últimas Asignaciones de Equipos')
            ->columns([
                Tables\Columns\TextColumn::make('asset.computer_code')
                    ->label('Cód. Activo')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Asignado A')
                    ->placeholder('Sin usuario'),
                Tables\Columns\TextColumn::make('office.acronym')
                    ->label('Oficina')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->label('Fecha Asignación')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->paginated(false);
    }
}
