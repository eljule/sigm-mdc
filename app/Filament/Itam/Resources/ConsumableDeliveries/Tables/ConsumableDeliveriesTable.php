<?php

namespace App\Filament\Itam\Resources\ConsumableDeliveries\Tables;

use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConsumableDeliveriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('N° Acta')
                    ->formatStateUsing(fn ($state, $record) => $record->delivery_number)
                    ->searchable(query: function ($query, string $search) {
                        $clean = preg_replace('/[^0-9]/', '', $search);
                        if ($clean !== '') {
                            $query->where('id', (int) $clean);
                        } else {
                            $query->where('id', 'LIKE', "%{$search}%");
                        }
                    })
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('delivered_at')
                    ->label('Fecha de Entrega')
                    ->dateTime('d/m/Y H:i A')
                    ->sortable(),

                TextColumn::make('delivered_by')
                    ->label('Entregado Por (Personal TI)')
                    ->searchable(),

                TextColumn::make('received_by')
                    ->label('Recibido Por')
                    ->searchable(),

                TextColumn::make('office.name')
                    ->label('Oficina Destino')
                    ->default('No especificada')
                    ->searchable(),

                TextColumn::make('items_count')
                    ->label('Cant. Insumos')
                    ->counts('items')
                    ->badge()
                    ->color('info')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Action::make('ver_acta')
                    ->label('Ver / Imprimir Acta')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('fichas.entrega_consumibles', ['id' => $record->id]))
                    ->openUrlInNewTab(),
            ]);
    }
}
