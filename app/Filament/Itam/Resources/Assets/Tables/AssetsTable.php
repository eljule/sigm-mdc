<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Models\AssetDecommission;
use App\Models\AssetAssignment;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('computer_code')
                    ->label('Cód. Informático')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('asset_code')
                    ->label('Cód. Patrimonial')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('model.brand.name')
                    ->label('Marca')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('model.name')
                    ->label('Modelo')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('serial_number')
                    ->label('S/N')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('color')
                    ->label('Color')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                        'muy bueno' => 'success',
                        'bueno' => 'info',
                        'regular' => 'warning',
                        'malo' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => strtoupper((string) $state))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Situación')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'DISPONIBLE',
                        'asignado' => 'ASIGNADO',
                        'mantenimiento' => 'MANTENIMIENTO',
                        'en evaluación', 'en evaluacion' => 'EN EVALUACIÓN',
                        'baja' => 'BAJA',
                        default => strtoupper((string) $state),
                    })
                    ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'success',
                        'asignado' => 'info',
                        'mantenimiento' => 'warning',
                        'en evaluación', 'en evaluacion' => 'warning',
                        'baja' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'heroicon-o-check-circle',
                        'asignado' => 'heroicon-o-user',
                        'mantenimiento' => 'heroicon-o-wrench-screwdriver',
                        'en evaluación', 'en evaluacion' => 'heroicon-o-magnifying-glass',
                        'baja' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('warranty_expiration')
                    ->label('Fin Garantía')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('asset_category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'muy bueno' => 'MUY BUENO',
                        'bueno' => 'BUENO',
                        'regular' => 'REGULAR',
                        'malo' => 'MALO',
                    ]),
                SelectFilter::make('status')
                    ->label('Situación')
                    ->options([
                        'disponible' => 'DISPONIBLE',
                        'asignado' => 'ASIGNADO',
                        'mantenimiento' => 'MANTENIMIENTO',
                        'en evaluación' => 'EN EVALUACIÓN',
                        'baja' => 'BAJA',
                    ]),
                SelectFilter::make('brand')
                    ->label('Marca')
                    ->relationship('model.brand', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('confirmar_baja')
                    ->label('Dar de Baja')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->modalHeading('Registro de Baja Definitiva')
                    ->modalSubmitActionLabel('Confirmar Baja')
                    ->modalWidth('2xl')
                    ->visible(fn ($record) => in_array(strtolower((string) ($record->status ?? '')), ['en evaluación', 'en evaluacion']) && (auth()->user()?->isTiStaff() ?? false))
                    ->form([
                        Select::make('decommissioned_by')
                            ->label('Técnico Responsable (TI)')
                            ->options(function () {
                                return \App\Models\User::all()
                                    ->filter(fn ($u) => $u->isTiStaff())
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->default(auth()->id())
                            ->required()
                            ->native(false)
                            ->helperText('Solo personal técnico del área de Desarrollo Tecnológico / TI está facultado.'),

                        Select::make('reason')
                            ->label('Motivo de Baja')
                            ->options(AssetDecommission::reasonOptions())
                            ->required()
                            ->native(false),

                        Select::make('resolution_type')
                            ->label('Tipo de Resolución')
                            ->options(AssetDecommission::resolutionTypeOptions())
                            ->required()
                            ->native(false)
                            ->helperText('Destino final del activo después de la baja.'),

                        Select::make('ticket_id')
                            ->label('Ticket de Origen (opcional)')
                            ->options(function () {
                                return \App\Models\Ticket::whereIn('status', ['resuelto', 'cerrado', 'Resuelto', 'Cerrado'])
                                    ->orderByDesc('id')
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn ($t) => [$t->id => "{$t->ticket_code} — {$t->title}"])
                                    ->toArray();
                            })
                            ->searchable()
                            ->nullable()
                            ->helperText('Si esta baja fue originada por un ticket, selecciónelo.'),

                        Textarea::make('evaluation_summary')
                            ->label('Dictamen Técnico')
                            ->placeholder('Describa detalladamente la evaluación técnica que determina la baja del activo...')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('authorized_by')
                            ->label('Autorizado por')
                            ->placeholder('Nombre del responsable que autoriza la baja')
                            ->helperText('Funcionario o jefe de área que autoriza este dictamen.')
                            ->nullable(),
                    ])
                    ->action(function ($record, array $data) {
                        $decommission = AssetDecommission::create([
                            'asset_id'           => $record->id,
                            'ticket_id'          => $data['ticket_id'] ?? null,
                            'decommissioned_by'  => $data['decommissioned_by'] ?? auth()->id(),
                            'authorized_by'      => $data['authorized_by'] ?? null,
                            'decommissioned_at'  => now(),
                            'reason'             => $data['reason'],
                            'resolution_type'    => $data['resolution_type'],
                            'evaluation_summary' => $data['evaluation_summary'] ?? null,
                            'notes'              => null,
                        ]);

                        $record->update([
                            'status' => 'baja',
                            'notes'  => trim(($record->notes ?? '') . "\nBaja definitiva registrada ({$decommission->ficha_number}) el " . now()->format('d/m/Y H:i') . ". Motivo: {$data['reason']}. Resolución: {$data['resolution_type']}."),
                        ]);

                        Notification::make()
                            ->title("Baja registrada — {$decommission->ficha_number}")
                            ->body('El activo fue dado de baja definitivamente.')
                            ->danger()
                            ->send();
                    }),

                Action::make('recuperar_activo')
                    ->label('Recuperar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Recuperar Activo')
                    ->modalDescription(function ($record) {
                        $lastAssignment = AssetAssignment::where('asset_id', $record->id)
                            ->whereNotNull('returned_at')
                            ->orderByDesc('returned_at')
                            ->first();

                        if ($lastAssignment) {
                            $user   = $lastAssignment->user?->name ?? "usuario #{$lastAssignment->user_id}";
                            $office = $lastAssignment->office?->name ?? '';
                            return "El activo fue evaluado positivamente. Se reactivará su asignación anterior a {$user}" . ($office ? " — {$office}" : '') . " y volverá al estado Asignado.";
                        }

                        return 'El activo fue evaluado positivamente. No tenía asignación previa, por lo que pasará al estado Disponible.';
                    })
                    ->modalSubmitActionLabel('Sí, recuperar activo')
                    ->visible(fn ($record) => in_array(strtolower((string) ($record->status ?? '')), ['en evaluación', 'en evaluacion']) && (auth()->user()?->isTiStaff() ?? false))
                    ->action(function ($record) {
                        $lastAssignment = AssetAssignment::where('asset_id', $record->id)
                            ->whereNotNull('returned_at')
                            ->orderByDesc('returned_at')
                            ->first();

                        if ($lastAssignment) {
                            $lastAssignment->update([
                                'returned_at' => null,
                                'notes'       => trim(($lastAssignment->notes ?? '') . "\nAsignación reactivada por recuperación del activo el " . now()->format('d/m/Y H:i') . '.'),
                            ]);
                            $record->update([
                                'status' => 'asignado',
                                'notes'  => trim(($record->notes ?? '') . "\nActivo recuperado por dictamen técnico el " . now()->format('d/m/Y H:i') . '. Asignación reactivada.'),
                            ]);
                            $userName = $lastAssignment->user?->name ?? "usuario #{$lastAssignment->user_id}";
                            Notification::make()
                                ->title("Activo recuperado — Asignado a {$userName}")
                                ->body('La asignación anterior fue reactivada.')
                                ->success()
                                ->send();
                        } else {
                            $record->update([
                                'status' => 'disponible',
                                'notes'  => trim(($record->notes ?? '') . "\nActivo recuperado y marcado como Disponible por dictamen técnico el " . now()->format('d/m/Y H:i') . '.'),
                            ]);
                            Notification::make()
                                ->title('Activo recuperado y disponible')
                                ->body('El activo no tenía asignación previa. Marcado como Disponible.')
                                ->success()
                                ->send();
                        }
                    }),

                Action::make('ver_ficha_baja')
                    ->label('Ficha Baja')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->visible(fn ($record) => strtolower((string) ($record->status ?? '')) === 'baja' && AssetDecommission::where('asset_id', $record->id)->exists())
                    ->url(fn ($record) => route('fichas.baja', ['id' => AssetDecommission::where('asset_id', $record->id)->latest()->value('id')]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
