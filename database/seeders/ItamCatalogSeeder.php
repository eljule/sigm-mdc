<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AssetBlock;
use App\Models\AssetBrand;
use App\Models\AssetCategory;
use App\Models\AssetCharacteristic;
use App\Models\AssetModel;
use Illuminate\Database\Seeder;

class ItamCatalogSeeder extends Seeder
{
    /**
     * Seed ITAM Catalogs: Categories, Brands, Models, Blocks, Characteristics.
     */
    public function run(): void
    {
        // 1. Categorías de Activos
        $categories = [
            ['id' => 1, 'name' => 'laptop', 'description' => 'laptops y computadoras portátiles'],
            ['id' => 2, 'name' => 'pc de escritorio', 'description' => 'computadoras de escritorio'],
            ['id' => 3, 'name' => 'teclado', 'description' => 'teclados de computadora'],
            ['id' => 4, 'name' => 'mouse', 'description' => 'mouses / ratones ópticos'],
            ['id' => 5, 'name' => 'monitor', 'description' => 'monitores y pantallas'],
            ['id' => 6, 'name' => 'servidor', 'description' => 'equipo que proporciona recursos para otros dispositivos'],
            ['id' => 7, 'name' => 'impresora', 'description' => 'equipo de impresion'],
        ];

        foreach ($categories as $cat) {
            AssetCategory::updateOrCreate(['id' => $cat['id']], $cat);
        }

        // 2. Marcas de Activos
        $brands = [
            ['id' => 1, 'name' => 'hp', 'description' => 'hewlett-packard'],
            ['id' => 2, 'name' => 'lenovo', 'description' => 'lenovo group'],
            ['id' => 3, 'name' => 'brother', 'description' => 'at your side'],
            ['id' => 6, 'name' => 'lg', 'description' => 'multinacional lg group'],
            ['id' => 7, 'name' => 'logitech', 'description' => 'multinacional dedicada a la electrónica que fabrica periféricos'],
            ['id' => 8, 'name' => 'advance', 'description' => 'advance peru let\'s move on
'],
        ];

        foreach ($brands as $brand) {
            AssetBrand::updateOrCreate(['id' => $brand['id']], $brand);
        }

        // 3. Modelos de Activos
        $models = [
            ['id' => 6, 'asset_brand_id' => 3, 'name' => 'mfc-t4500dw', 'description' => 'MULTIFUNCIONAL DE INYECCIÓN DE TINTA A COLOR PARA DOCUMENTOS A3'],
            ['id' => 7, 'asset_brand_id' => 3, 'name' => 'dcp-l5660dn', 'description' => 'multifuncional láser monocromático'],
            ['id' => 8, 'asset_brand_id' => 1, 'name' => 'compaq pro 4300', 'description' => 'hp compaq pro 4300 sff business desktop'],
            ['id' => 9, 'asset_brand_id' => 6, 'name' => '20en43sa', 'description' => 'monitor lg'],
            ['id' => 10, 'asset_brand_id' => 7, 'name' => 'k120', 'description' => 'teclado logitech'],
            ['id' => 11, 'asset_brand_id' => 7, 'name' => 'b100', 'description' => 'mouse logitech'],
            ['id' => 12, 'asset_brand_id' => 2, 'name' => 'thinkcentre m720s', 'description' => 'cpu lenovo'],
            ['id' => 13, 'asset_brand_id' => 2, 'name' => 't2224da', 'description' => 'monitor lenovo'],
            ['id' => 14, 'asset_brand_id' => 8, 'name' => 'vp3570', 'description' => 'cpu advance'],
        ];

        foreach ($models as $model) {
            AssetModel::updateOrCreate(['id' => $model['id']], $model);
        }

        // 4. Bloques de Características Dinámicas
        $blocks = [
            ['id' => 1, 'asset_category_id' => 2, 'name' => 'especificaciones tecnicas', 'sort_order' => 1],
            ['id' => 2, 'asset_category_id' => 2, 'name' => 'red', 'sort_order' => 2],
            ['id' => 3, 'asset_category_id' => 1, 'name' => 'especificaciones técnicas', 'sort_order' => 1],
            ['id' => 4, 'asset_category_id' => 1, 'name' => 'red y estado', 'sort_order' => 2],
            ['id' => 5, 'asset_category_id' => 5, 'name' => 'detalles de pantalla', 'sort_order' => 1],
            ['id' => 6, 'asset_category_id' => 6, 'name' => 'especificaciones técnicas', 'sort_order' => 1],
            ['id' => 7, 'asset_category_id' => 6, 'name' => 'red y estado', 'sort_order' => 2],
            ['id' => 8, 'asset_category_id' => 7, 'name' => 'especificaciones tecnicas', 'sort_order' => 1],
            ['id' => 9, 'asset_category_id' => 7, 'name' => 'red', 'sort_order' => 2],
        ];

        foreach ($blocks as $block) {
            AssetBlock::updateOrCreate(['id' => $block['id']], $block);
        }

        // 5. Características Dinámicas
        $characteristics = [
            ['id' => 1, 'asset_block_id' => 1, 'name' => 'procesador', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 1],
            ['id' => 2, 'asset_block_id' => 1, 'name' => 'memoria ram', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 3],
            ['id' => 3, 'asset_block_id' => 1, 'name' => 'hdd', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 4],
            ['id' => 4, 'asset_block_id' => 2, 'name' => 'dirección ip', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 1],
            ['id' => 5, 'asset_block_id' => 2, 'name' => 'dirección mac', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 2],
            ['id' => 6, 'asset_block_id' => 3, 'name' => 'procesador', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 1],
            ['id' => 7, 'asset_block_id' => 3, 'name' => 'memoria ram', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 2],
            ['id' => 8, 'asset_block_id' => 3, 'name' => 'almacenamiento', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 3],
            ['id' => 9, 'asset_block_id' => 4, 'name' => 'dirección ip', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 1],
            ['id' => 10, 'asset_block_id' => 4, 'name' => 'dirección mac', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 2],
            ['id' => 11, 'asset_block_id' => 5, 'name' => 'resolución y tamaño', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 1],
            ['id' => 12, 'asset_block_id' => 6, 'name' => 'procesador', 'type' => 'text', 'options' => null, 'is_required' => true, 'sort_order' => 1],
            ['id' => 13, 'asset_block_id' => 6, 'name' => 'memoria ram', 'type' => 'text', 'options' => null, 'is_required' => true, 'sort_order' => 2],
            ['id' => 14, 'asset_block_id' => 6, 'name' => 'almacenamiento', 'type' => 'text', 'options' => null, 'is_required' => true, 'sort_order' => 3],
            ['id' => 15, 'asset_block_id' => 7, 'name' => 'dirección ip', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 1],
            ['id' => 16, 'asset_block_id' => 7, 'name' => 'dirección mac', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 2],
            ['id' => 17, 'asset_block_id' => 8, 'name' => 'tipo de impresion', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 1],
            ['id' => 18, 'asset_block_id' => 8, 'name' => 'formato de impresion', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 2],
            ['id' => 19, 'asset_block_id' => 9, 'name' => 'direccion ip', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 1],
            ['id' => 20, 'asset_block_id' => 9, 'name' => 'direccion mac', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 2],
            ['id' => 22, 'asset_block_id' => 1, 'name' => 'velocidad procesador', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 2],
            ['id' => 23, 'asset_block_id' => 1, 'name' => 'ssd', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 5],
            ['id' => 24, 'asset_block_id' => 1, 'name' => 'numero procesador', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 3],
            ['id' => 25, 'asset_block_id' => 1, 'name' => 'tecnologia ssd', 'type' => 'text', 'options' => null, 'is_required' => false, 'sort_order' => 6],
        ];

        foreach ($characteristics as $char) {
            AssetCharacteristic::updateOrCreate(['id' => $char['id']], $char);
        }
    }
}
