<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('office_id')->nullable()->constrained('offices')->onDelete('set null');
            $table->string('borrower_name');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('returned_at')->nullable();
            $table->string('status')->default('pending'); // pending, active, returned, cancelled, overdue
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_loans');
    }
};
