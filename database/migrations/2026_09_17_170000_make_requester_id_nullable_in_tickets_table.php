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
            $table->foreignId('requester_id')->nullable()->change();
            $table->string('requester_name', 150)->nullable();
            $table->string('contact_phone', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['requester_name', 'contact_phone']);
            $table->foreignId('requester_id')->nullable(false)->change();
        });
    }
};
