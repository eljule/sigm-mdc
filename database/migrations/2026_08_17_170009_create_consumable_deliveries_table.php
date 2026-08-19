<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumable_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivered_by');
            $table->string('received_by');
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->timestamp('delivered_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('consumable_delivery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumable_delivery_id')->constrained('consumable_deliveries')->cascadeOnDelete();
            $table->foreignId('consumable_id')->constrained('consumables')->restrictOnDelete();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumable_delivery_items');
        Schema::dropIfExists('consumable_deliveries');
    }
};