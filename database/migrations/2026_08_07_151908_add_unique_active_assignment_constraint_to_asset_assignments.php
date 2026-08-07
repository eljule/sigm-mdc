<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega un índice único parcial sobre asset_id WHERE returned_at IS NULL.
     * Esto garantiza que un activo solo puede tener UNA asignación activa a la vez.
     * Las asignaciones históricas (returned_at NOT NULL) no están restringidas.
     */
    public function up(): void
    {
        // Primero eliminamos el índice compuesto no-único anterior
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->dropIndex('asset_assignments_asset_id_user_id_index');
        });

        // Índice único parcial — solo soportado nativamente en PostgreSQL
        // Un mismo asset_id no puede tener dos filas con returned_at = NULL
        DB::statement('
            CREATE UNIQUE INDEX asset_assignments_active_unique
            ON asset_assignments (asset_id)
            WHERE returned_at IS NULL
        ');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS asset_assignments_active_unique');

        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->index(['asset_id', 'user_id']);
        });
    }
};
