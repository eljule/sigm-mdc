<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetCharacteristicValue;
use App\Models\Software;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Seed real hierarchical Assets, dynamic Characteristic Values, and Software assignments.
     */
    public function run(): void
    {
        // 1. Activos Principales (Padres)
        $parentAssets = [
            ['id' => 6, 'parent_id' => null, 'asset_category_id' => 7, 'asset_model_id' => 6, 'computer_code' => 'COD-TI-0001', 'asset_code' => null, 'serial_number' => 'U65160M2H881961', 'color' => 'blaco', 'estado' => 'bueno', 'status' => 'disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => null],
            ['id' => 7, 'parent_id' => null, 'asset_category_id' => 7, 'asset_model_id' => 7, 'computer_code' => 'COD-TI-0007', 'asset_code' => null, 'serial_number' => 'U66654M3N204899', 'color' => null, 'estado' => 'bueno', 'status' => 'Disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => null],
            ['id' => 8, 'parent_id' => null, 'asset_category_id' => 2, 'asset_model_id' => 8, 'computer_code' => 'COD-TI-0008', 'asset_code' => '740899500011', 'serial_number' => 'MXL2381BKS', 'color' => 'negro', 'estado' => 'regular', 'status' => 'disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => 'equipo en estado regular'],
            ['id' => 12, 'parent_id' => null, 'asset_category_id' => 2, 'asset_model_id' => 12, 'computer_code' => 'COD-TI-0012', 'asset_code' => '740899500300', 'serial_number' => 'S01A00', 'color' => null, 'estado' => 'regular', 'status' => 'disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => 'equipo en estado regular'],
            ['id' => 16, 'parent_id' => null, 'asset_category_id' => 2, 'asset_model_id' => 14, 'computer_code' => 'COD-TI-0016', 'asset_code' => null, 'serial_number' => 'PE240282500001', 'color' => null, 'estado' => 'bueno', 'status' => 'Disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => 'equipo en estado muy bueno'],
        ];

        foreach ($parentAssets as $assetData) {
            Asset::updateOrCreate(['id' => $assetData['id']], $assetData);
        }

        // 2. Componentes y Periféricos Hijos (con parent_id)
        $childAssets = [
            ['id' => 9, 'parent_id' => 8, 'asset_category_id' => 5, 'asset_model_id' => 9, 'computer_code' => 'COD-TI-0009', 'asset_code' => '740877500010', 'serial_number' => '303NDSKA9486', 'color' => null, 'estado' => 'bueno', 'status' => 'Disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => null],
            ['id' => 10, 'parent_id' => 8, 'asset_category_id' => 3, 'asset_model_id' => 10, 'computer_code' => 'COD-TI-0010', 'asset_code' => null, 'serial_number' => '2234MR03A548', 'color' => null, 'estado' => 'bueno', 'status' => 'Disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => null],
            ['id' => 11, 'parent_id' => 8, 'asset_category_id' => 4, 'asset_model_id' => 11, 'computer_code' => 'COD-TI-0011', 'asset_code' => null, 'serial_number' => 'HS224HB', 'color' => null, 'estado' => 'bueno', 'status' => 'Disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => null],
            ['id' => 13, 'parent_id' => 12, 'asset_category_id' => 5, 'asset_model_id' => 13, 'computer_code' => 'COD-TI-0013', 'asset_code' => '740899500290', 'serial_number' => 'V5B5109', 'color' => null, 'estado' => 'bueno', 'status' => 'disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => null],
            ['id' => 14, 'parent_id' => 12, 'asset_category_id' => 3, 'asset_model_id' => 10, 'computer_code' => 'COD-TI-0014', 'asset_code' => '740895000252', 'serial_number' => '1807MR11C888', 'color' => null, 'estado' => 'bueno', 'status' => 'Disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => null],
            ['id' => 15, 'parent_id' => 12, 'asset_category_id' => 4, 'asset_model_id' => 11, 'computer_code' => 'COD-TI-0015', 'asset_code' => null, 'serial_number' => 'HS224HB6', 'color' => null, 'estado' => 'bueno', 'status' => 'Disponible', 'warranty_expiration' => null, 'purchase_date' => null, 'notes' => null],
        ];

        foreach ($childAssets as $assetData) {
            Asset::updateOrCreate(['id' => $assetData['id']], $assetData);
        }

        // 3. Valores de Características Técnicas Dinámicas
        $characteristicValues = [
            ['id' => 12, 'asset_id' => 6, 'asset_characteristic_id' => 17, 'value' => 'inyeccion de tinta'],
            ['id' => 13, 'asset_id' => 6, 'asset_characteristic_id' => 18, 'value' => 'a3'],
            ['id' => 14, 'asset_id' => 7, 'asset_characteristic_id' => 17, 'value' => 'laser'],
            ['id' => 15, 'asset_id' => 7, 'asset_characteristic_id' => 18, 'value' => 'a4'],
            ['id' => 16, 'asset_id' => 7, 'asset_characteristic_id' => 19, 'value' => '192.168.0.119'],
            ['id' => 17, 'asset_id' => 7, 'asset_characteristic_id' => 20, 'value' => '94:dd:f8:18:d8:85'],
            ['id' => 18, 'asset_id' => 8, 'asset_characteristic_id' => 1, 'value' => 'intel core i5'],
            ['id' => 19, 'asset_id' => 8, 'asset_characteristic_id' => 2, 'value' => '4 gb'],
            ['id' => 20, 'asset_id' => 8, 'asset_characteristic_id' => 3, 'value' => '500 gb'],
            ['id' => 21, 'asset_id' => 8, 'asset_characteristic_id' => 4, 'value' => '192.168.0.35'],
            ['id' => 22, 'asset_id' => 8, 'asset_characteristic_id' => 5, 'value' => '78-e3-b5-b0-a6-9e'],
            ['id' => 23, 'asset_id' => 8, 'asset_characteristic_id' => 22, 'value' => '2.9 ghz'],
            ['id' => 24, 'asset_id' => 8, 'asset_characteristic_id' => 23, 'value' => null],
            ['id' => 25, 'asset_id' => 9, 'asset_characteristic_id' => 11, 'value' => '14 pulgadas'],
            ['id' => 26, 'asset_id' => 12, 'asset_characteristic_id' => 1, 'value' => 'intel core i5'],
            ['id' => 27, 'asset_id' => 12, 'asset_characteristic_id' => 22, 'value' => '2.8'],
            ['id' => 28, 'asset_id' => 12, 'asset_characteristic_id' => 2, 'value' => '4 gb'],
            ['id' => 29, 'asset_id' => 12, 'asset_characteristic_id' => 3, 'value' => '1 tb'],
            ['id' => 30, 'asset_id' => 12, 'asset_characteristic_id' => 4, 'value' => '192.168.0.227'],
            ['id' => 31, 'asset_id' => 12, 'asset_characteristic_id' => 5, 'value' => '30-9c-23-be-91-cf'],
            ['id' => 32, 'asset_id' => 12, 'asset_characteristic_id' => 23, 'value' => null],
            ['id' => 33, 'asset_id' => 13, 'asset_characteristic_id' => 11, 'value' => '17 pulgadas'],
            ['id' => 34, 'asset_id' => 16, 'asset_characteristic_id' => 1, 'value' => 'intel core i5'],
            ['id' => 35, 'asset_id' => 16, 'asset_characteristic_id' => 22, 'value' => '2.5 ghz'],
            ['id' => 36, 'asset_id' => 16, 'asset_characteristic_id' => 2, 'value' => '8 gb'],
            ['id' => 37, 'asset_id' => 16, 'asset_characteristic_id' => 23, 'value' => '1 tb'],
            ['id' => 38, 'asset_id' => 16, 'asset_characteristic_id' => 4, 'value' => '192.168.0.217'],
            ['id' => 39, 'asset_id' => 16, 'asset_characteristic_id' => 5, 'value' => 'd8-43-ae-4a-47-32'],
            ['id' => 40, 'asset_id' => 8, 'asset_characteristic_id' => 24, 'value' => '3470s'],
            ['id' => 41, 'asset_id' => 8, 'asset_characteristic_id' => 25, 'value' => null],
            ['id' => 44, 'asset_id' => 6, 'asset_characteristic_id' => 19, 'value' => null],
            ['id' => 45, 'asset_id' => 6, 'asset_characteristic_id' => 20, 'value' => null],
        ];

        foreach ($characteristicValues as $val) {
            AssetCharacteristicValue::updateOrCreate(['id' => $val['id']], $val);
        }

        // 4. Asignación de Software a Activos (asset_software)
        $assetSoftwares = [
            ['asset_id' => 8, 'software_id' => 3, 'installed_at' => '2026-09-14'],
            ['asset_id' => 12, 'software_id' => 4, 'installed_at' => '2026-09-14'],
            ['asset_id' => 16, 'software_id' => 1, 'installed_at' => '2026-09-15'],
        ];

        foreach ($assetSoftwares as $rel) {
            $asset = Asset::find($rel['asset_id']);
            if ($asset) {
                $asset->softwares()->syncWithoutDetaching([
                    $rel['software_id'] => ['installed_at' => $rel['installed_at']]
                ]);
            }
        }
    }
}
