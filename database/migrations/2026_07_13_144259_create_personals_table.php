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
        Schema::create('personals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types')->onDelete('restrict');
            $table->string('document_number', 20)->unique();
            $table->string('first_name', 100)->nullable();
            $table->string('paternal_surname', 100)->nullable();
            $table->string('maternal_surname', 100)->nullable();
            $table->string('full_name', 255);
            $table->string('gender', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address', 255)->nullable();
            $table->char('ubigeo_code', 6)->nullable();
            $table->foreign('ubigeo_code')->references('code')->on('ubigeos')->onDelete('set null');
            $table->foreignId('labor_condition_id')->nullable()->constrained('labor_conditions')->onDelete('set null');
            $table->foreignId('office_id')->nullable()->constrained('offices')->onDelete('set null');
            $table->string('position', 150)->nullable();
            $table->date('hire_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('document_number');
            $table->index('office_id');
            $table->index('labor_condition_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personals');
    }
};
