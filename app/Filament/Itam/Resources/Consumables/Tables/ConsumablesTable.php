<?php

namespace App\Filament\Itam\Resources\Consumables\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ConsumablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre del Insumo / Consumible')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Stock Actual')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->stock <= $record->min_stock ? 'danger' : 'success'),
                TextColumn::make('unit')
                    ->label('Unidad de Medida'),
                TextColumn::make('min_stock')
                    ->label('Stock Mínimo Alerta')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registrado el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('generar_acta_entrega')
                        ->label('Generar Acta de Entrega')
                        ->icon('heroicon-o-document-check')
                        ->color('primary')
                        ->modalHeading('Generación de Acta de Entrega de Insumos')
                        ->modalDescription('Complete los datos de entrega y las cantidades a entregar de los insumos seleccionados.')
                        ->modalSubmitActionLabel('Confirmar y Generar Acta')
                        ->modalWidth('3xl')
                        ->form(function (Collection $records) {
                            return [
                                Section::make('Información del Acta de Entrega')
                                    ->description('Datos del personal y dependencia involucrados en la entrega.')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('delivered_by')
                                            ->label('Persona que Entrega (Personal TI)')
                                            ->default(fn () => auth()->user()?->name ?? 'Personal TI')
                                            ->required(),

                                        TextInput::make('received_by')
                                            ->label('Persona que Recibe (Nombre / DNI / Cargo)')
                                            ->placeholder('Ej: Ing. María López - Especialista')
                                            ->required(),

                                        Select::make('office_id')
                                            ->label('Oficina / Dependencia de Destino')
                                            ->options(fn () => \App\Models\Office::pluck('name', 'id')->toArray())
                                            ->searchable()
                                            ->nullable()
                                            ->columnSpanFull(),

                                        DatePicker::make('delivered_at')
                                            ->label('Fecha de Entrega')
                                            ->default(now())
                                            ->required(),

                                        Textarea::make('notes')
                                            ->label('Observaciones / Detalle')
                                            ->placeholder('Notas u observaciones opcionales...')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Cantidades a Entregar')
                                    ->description('Ingrese la cantidad que se entregará de cada insumo seleccionado.')
                                    ->columns(2)
                                    ->schema(function () use ($records) {
                                        $fields = [];
                                        foreach ($records as $consumable) {
                                            $fields[] = TextInput::make("qty_{$consumable->id}")
                                                ->label("{$consumable->name}")
                                                ->helperText("Stock disponible: {$consumable->stock} {$consumable->unit}")
                                                ->numeric()
                                                ->required()
                                                ->default(1)
                                                ->minValue(1)
                                                ->maxValue($consumable->stock);
                                        }
                                        return $fields;
                                    }),
                            ];
                        })
                        ->action(function (Collection $records, array $data) {
                            // 1. Validar que ninguna cantidad sea inválida o supere el stock disponible
                            foreach ($records as $consumable) {
                                $qtyKey = "qty_{$consumable->id}";
                                $qty = (int) ($data[$qtyKey] ?? 0);

                                if ($qty <= 0) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Cantidad inválida')
                                        ->body("La cantidad para '{$consumable->name}' debe ser mayor a 0.")
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                if ($qty > $consumable->stock) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Stock insuficiente')
                                        ->body("La cantidad ({$qty}) para '{$consumable->name}' supera el stock actual ({$consumable->stock}).")
                                        ->danger()
                                        ->send();
                                    return;
                                }
                            }

                            // 2. Crear la entrega en BD
                            $delivery = \App\Models\ConsumableDelivery::create([
                                'delivered_by' => $data['delivered_by'],
                                'received_by'  => $data['received_by'],
                                'office_id'    => $data['office_id'] ?? null,
                                'delivered_at' => $data['delivered_at'] ?? now(),
                                'notes'        => $data['notes'] ?? null,
                            ]);

                            // 3. Crear items y descontar stock
                            foreach ($records as $consumable) {
                                $qty = (int) $data["qty_{$consumable->id}"];

                                \App\Models\ConsumableDeliveryItem::create([
                                    'consumable_delivery_id' => $delivery->id,
                                    'consumable_id'          => $consumable->id,
                                    'quantity'               => $qty,
                                ]);

                                $consumable->decrement('stock', $qty);
                            }

                            \Filament\Notifications\Notification::make()
                                ->title("Acta {$delivery->delivery_number} Generada")
                                ->body('Se descontaron los insumos del stock y se generó el acta imprimible.')
                                ->success()
                                ->send();

                            $url = route('fichas.entrega_consumibles', ['id' => $delivery->id]);
                            return redirect()->away($url);
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
