<?php

namespace App\Filament\Helpdesk\Widgets;

use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTicketsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::query()->latest()->limit(5)
            )
            ->heading('Últimas Incidencias Registradas')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_code')
                    ->label('Cód. Ticket')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Asunto')
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Abierto' => 'danger',
                        'En Proceso' => 'warning',
                        'Resuelto' => 'info',
                        'Cerrado' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->paginated(false);
    }
}
