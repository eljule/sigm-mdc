<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('color', 50)->nullable()->after('serial_number');
            $table->string('estado', 20)->default('bueno')->after('color');
        });

        // Migrar datos existentes desde asset_characteristic_values
        $colorValues = DB::table('asset_characteristic_values as v')
            ->join('asset_characteristics as c', 'v.asset_characteristic_id', '=', 'c.id')
            ->whereRaw("LOWER(c.name) = 'color'")
            ->select('v.asset_id', 'v.value')
            ->get();

        foreach ($colorValues as $cv) {
            if (! empty($cv->value)) {
                DB::table('assets')
                    ->where('id', $cv->asset_id)
                    ->update(['color' => strtolower(trim($cv->value))]);
            }
        }

        $estadoValues = DB::table('asset_characteristic_values as v')
            ->join('asset_characteristics as c', 'v.asset_characteristic_id', '=', 'c.id')
            ->whereRaw("LOWER(c.name) = 'estado'")
            ->select('v.asset_id', 'v.value')
            ->get();

        foreach ($estadoValues as $ev) {
            if (! empty($ev->value)) {
                $val = strtolower(trim($ev->value));
                if (in_array($val, ['bueno', 'regular', 'malo'])) {
                    DB::table('assets')
                        ->where('id', $ev->asset_id)
                        ->update(['estado' => $val]);
                }
            }
        }

        // Eliminar bloques y características redundantes 'color / estado'
        $redundantBlocks = DB::table('asset_blocks')
            ->whereRaw("LOWER(name) LIKE '%color%'")
            ->pluck('id');

        if ($redundantBlocks->isNotEmpty()) {
            $redundantChars = DB::table('asset_characteristics')
                ->whereIn('asset_block_id', $redundantBlocks)
                ->pluck('id');

            if ($redundantChars->isNotEmpty()) {
                DB::table('asset_characteristic_values')
                    ->whereIn('asset_characteristic_id', $redundantChars)
                    ->delete();

                DB::table('asset_characteristics')
                    ->whereIn('id', $redundantChars)
                    ->delete();
            }

            DB::table('asset_blocks')
                ->whereIn('id', $redundantBlocks)
                ->delete();
        }
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['color', 'estado']);
        });
    }
};
