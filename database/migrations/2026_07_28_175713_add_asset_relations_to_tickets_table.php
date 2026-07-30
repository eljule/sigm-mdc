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
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('affected_asset_id')->nullable()->after('office_id')->constrained('assets')->nullOnDelete();
            $table->foreignId('replacement_asset_id')->nullable()->after('affected_asset_id')->constrained('assets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('affected_asset_id');
            $table->dropConstrainedForeignId('replacement_asset_id');
        });
    }
};
