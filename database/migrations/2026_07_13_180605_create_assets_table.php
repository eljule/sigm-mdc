<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code', 50)->unique();
            $table->string('category', 50); // Servidor, PC, Laptop, Impresora, Switch, etc.
            $table->string('brand', 50);
            $table->string('model', 100);
            $table->string('serial_number', 100)->unique();
            $table->string('processor', 100)->nullable();
            $table->string('ram', 50)->nullable();
            $table->string('storage', 150)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('mac_address', 17)->nullable();
            $table->string('status', 30)->default('Disponible'); // Disponible, Asignado, Mantenimiento, Baja
            $table->date('warranty_expiration')->nullable();
            $table->date('purchase_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexing for faster search/filtering
            $table->index('status');
            $table->index('warranty_expiration');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
