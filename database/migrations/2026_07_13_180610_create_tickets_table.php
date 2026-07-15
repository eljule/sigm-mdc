<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 20)->unique();
            $table->foreignId('category_id')->constrained('ticket_categories')->onDelete('restrict');
            $table->foreignId('requester_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('office_id')->constrained('offices')->onDelete('restrict');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title', 150);
            $table->text('description');
            $table->string('priority', 20); // Baja, Media, Alta
            $table->string('status', 30)->default('Abierto'); // Abierto, En Proceso, Esperando Terceros, Resuelto, Cerrado
            $table->text('solution_applied')->nullable();
            $table->timestamp('sla_expires_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            // Indexes for tickets queue
            $table->index('ticket_code');
            $table->index('status');
            $table->index('priority');
            $table->index('sla_expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
