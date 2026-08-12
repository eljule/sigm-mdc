<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de registros formales de baja de activos.
     * Cada baja genera un número correlativo FICH-BAJA-{id con ceros}.
     */
    public function up(): void
    {
        Schema::create('asset_decommissions', function (Blueprint $table) {
            $table->id();

            // Activo dado de baja
            $table->foreignId('asset_id')
                ->constrained('assets')
                ->restrictOnDelete();

            // Ticket de origen (si la baja viene de un proceso de ticket)
            $table->foreignId('ticket_id')
                ->nullable()
                ->constrained('tickets')
                ->nullOnDelete();

            // Usuario que ejecutó la baja (técnico)
            $table->foreignId('decommissioned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Nombre del funcionario que autoriza la baja (puede ser externo)
            $table->string('authorized_by')->nullable();

            // Fecha y hora oficial de la baja
            $table->timestamp('decommissioned_at');

            // Motivo de baja
            $table->string('reason'); // obsolescencia, daño, robo, etc.

            // Tipo de resolución final del activo
            $table->string('resolution_type'); // chatarreo, donación, venta, etc.

            // Dictamen técnico detallado
            $table->text('evaluation_summary')->nullable();

            // Observaciones adicionales
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('asset_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_decommissions');
    }
};
