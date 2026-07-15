<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_software', function (Blueprint $table) {
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('software_id')->constrained('softwares')->onDelete('restrict');
            $table->date('installed_at')->nullable();
            $table->primary(['asset_id', 'software_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_software');
    }
};
