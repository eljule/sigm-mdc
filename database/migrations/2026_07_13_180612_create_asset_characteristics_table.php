<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_characteristics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_block_id')->constrained('asset_blocks')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('type', 30); // text, number, date, select, boolean
            $table->text('options')->nullable(); // comma-separated options
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_characteristics');
    }
};
