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
        Schema::create('ubigeos', function (Blueprint $table) {
            $table->char('code', 6)->primary();
            $table->string('department', 100);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // PostgreSQL B-tree index for composite geographic searches
            $table->index(['department', 'province', 'district']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubigeos');
    }
};
