<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->integer('stock')->default(0);
            $table->string('unit', 30)->default('Unidades');
            $table->integer('min_stock')->default(0);
            $table->timestamps();
        });

        Schema::create('ticket_consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('consumable_id')->constrained('consumables')->onDelete('restrict');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Index
            $table->index(['ticket_id', 'consumable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_consumables');
        Schema::dropIfExists('consumables');
    }
};
