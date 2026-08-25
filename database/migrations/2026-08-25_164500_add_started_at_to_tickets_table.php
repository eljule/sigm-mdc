<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('tickets', 'started_at')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->timestamp('started_at')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tickets', 'started_at')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('started_at');
            });
        }
    }
};
