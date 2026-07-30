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
            $table->string('user_category', 50)->nullable()->after('category_id');
            $table->string('impact', 50)->nullable()->after('priority');
            $table->text('attachments')->nullable()->after('description');
            $table->text('diagnosis')->nullable()->after('solution_applied');
            $table->string('root_cause', 100)->nullable()->after('diagnosis');
            $table->boolean('save_to_knowledge_base')->default(false)->after('root_cause');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'user_category',
                'impact',
                'attachments',
                'diagnosis',
                'root_cause',
                'save_to_knowledge_base',
            ]);
        });
    }
};
