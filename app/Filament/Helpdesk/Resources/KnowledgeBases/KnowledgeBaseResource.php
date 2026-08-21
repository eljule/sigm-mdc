<?php

namespace App\Filament\Helpdesk\Resources\KnowledgeBases;

use App\Filament\Helpdesk\Resources\KnowledgeBases\Pages\CreateKnowledgeBase;
use App\Filament\Helpdesk\Resources\KnowledgeBases\Pages\EditKnowledgeBase;
use App\Filament\Helpdesk\Resources\KnowledgeBases\Pages\ListKnowledgeBases;
use App\Filament\Helpdesk\Resources\KnowledgeBases\Schemas\KnowledgeBaseForm;
use App\Filament\Helpdesk\Resources\KnowledgeBases\Tables\KnowledgeBasesTable;
use App\Models\KnowledgeBase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KnowledgeBaseResource extends Resource
{
    protected static ?string $model = KnowledgeBase::class;

    protected static ?string $modelLabel = 'base de conocimiento';

    protected static ?string $pluralModelLabel = 'base de conocimiento';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Base de Conocimientos';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('consultar-base-conocimiento') ||
               $user->can('gestionar-base-conocimiento') ||
               $user->can('consultar-kb');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('insertar-kb') ||
               $user->can('gestionar-base-conocimiento');
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('modificar-kb') ||
               $user->can('gestionar-base-conocimiento');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('eliminar-kb') ||
               $user->can('gestionar-base-conocimiento');
    }

    public static function form(Schema $schema): Schema
    {
        return KnowledgeBaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KnowledgeBasesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListKnowledgeBases::route('/'),
            'create' => CreateKnowledgeBase::route('/create'),
            'edit'   => EditKnowledgeBase::route('/{record}/edit'),
        ];
    }
}
