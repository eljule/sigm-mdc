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
use Illuminate\Contracts\Support\Htmlable;

class KnowledgeBaseResource extends Resource
{
    protected static ?string $model = KnowledgeBase::class;

    protected static ?string $modelLabel = 'base de conocimiento';

    protected static ?string $pluralModelLabel = 'base de conocimiento';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    /**
     * Solo técnicos de soporte y administradores pueden acceder a este recurso.
     * Los usuarios con rol 'Usuario Reportante' deben usar el portal /soporte.
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->hasRole('Técnico de Soporte') || $user->hasRole('Administrador Central');
    }

    public static function form(Schema $schema): Schema
    {
        return KnowledgeBaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KnowledgeBasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKnowledgeBases::route('/'),
            'create' => CreateKnowledgeBase::route('/create'),
            'edit' => EditKnowledgeBase::route('/{record}/edit'),
        ];
    }
}
