<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Roles\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\PermissionRegistrar;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $title = 'Permisos';

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
                    ->label('Permiso')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelectOptionsQuery(function (Builder $query) {
                        $subsystemId = $this->getOwnerRecord()->subsystem_id;
                        
                        return $query->where('subsystem_id', $subsystemId);
                    })
                    ->preloadRecordSelect()
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
