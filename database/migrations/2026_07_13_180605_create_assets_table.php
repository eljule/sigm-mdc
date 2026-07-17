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
            $table->foreignId('parent_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->foreignId('asset_category_id')->constrained('asset_categories')->restrictOnDelete();
            $table->foreignId('asset_model_id')->constrained('asset_models')->restrictOnDelete();
            $table->string('computer_code', 50)->unique();
            $table->string('asset_code', 50)->unique()->nullable();
            $table->string('serial_number', 100)->unique();
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
