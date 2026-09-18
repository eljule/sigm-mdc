<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Models\Subsystem;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\PermissionRegistrar;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'allRoles';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Rol')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subsystem.name')
                    ->label('Subsistema')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $role = \App\Models\Role::find($state);
                                    if ($role && $role->subsystem_id) {
                                        $set('subsystem_id', $role->subsystem_id);
                                    }
                                }
                            }),
                        Select::make('subsystem_id')
                            ->label('Subsistema')
                            ->options(Subsystem::all()->pluck('name', 'id'))
                            ->required()
                            ->default(fn (callable $get) => \App\Models\Role::find($get('recordId'))?->subsystem_id)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Asignado automáticamente según el subsistema del rol.'),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        if (! empty($data['recordId'])) {
                            $role = \App\Models\Role::find($data['recordId']);
                            if ($role && $role->subsystem_id) {
                                $data['subsystem_id'] = $role->subsystem_id;
                            }
                        }

                        return $data;
                    })
                    ->after(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions()),
            ])
            ->recordActions([
                DetachAction::make()
                    ->after(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->after(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions()),
                ]),
            ]);
    }
}
