<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega la columna subsystem_id a la tabla permissions de Spatie,
     * permitiendo asociar cada permiso a un subsistema del sistema SIGM.
     */
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreignId('subsystem_id')
                ->nullable()
                ->after('id')
                ->constrained('subsystems')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['subsystem_id']);
            $table->dropColumn('subsystem_id');
        });
    }
};
