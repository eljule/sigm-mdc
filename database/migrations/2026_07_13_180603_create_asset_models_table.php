<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_brand_id')->constrained('asset_brands')->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['asset_brand_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_models');
    }
};
