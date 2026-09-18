<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Consumable;
use Illuminate\Database\Seeder;

class ConsumableSeeder extends Seeder
{
    /**
     * Seed ITAM consumables and spare parts.
     */
    public function run(): void
    {
        $consumables = [
            ['id' => 1, 'name' => 'cable vga 1.8m', 'stock' => 15, 'unit' => 'unidades', 'min_stock' => 3],
            ['id' => 2, 'name' => 'cable de red cat6 3m', 'stock' => 40, 'unit' => 'unidades', 'min_stock' => 5],
            ['id' => 3, 'name' => 'conector rj45 amp', 'stock' => 100, 'unit' => 'unidades', 'min_stock' => 10],
            ['id' => 4, 'name' => 'tóner hp laserjet 85a', 'stock' => 8, 'unit' => 'unidades', 'min_stock' => 2],
        ];

        foreach ($consumables as $item) {
            Consumable::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
