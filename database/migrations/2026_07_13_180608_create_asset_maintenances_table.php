<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('type', 30); // Preventivo, Correctivo
            $table->date('scheduled_date');
            $table->date('performed_date')->nullable();
            $table->text('description');
            $table->text('technician_notes')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->timestamps();

            // Indexes for scheduling
            $table->index('scheduled_date');
            $table->index('performed_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
