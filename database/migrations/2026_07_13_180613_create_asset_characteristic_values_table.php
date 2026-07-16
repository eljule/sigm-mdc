<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_characteristic_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->foreignId('asset_characteristic_id')->constrained('asset_characteristics')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['asset_id', 'asset_characteristic_id'], 'asset_id_char_id_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_characteristic_values');
    }
};
