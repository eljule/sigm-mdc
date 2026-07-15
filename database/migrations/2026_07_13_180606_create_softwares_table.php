<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('softwares', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('version', 50)->nullable();
            $table->string('license_type', 50); // OEM, Volumen, Suscripción, Libre, etc.
            $table->text('license_key')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('max_activations')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('softwares');
    }
};
