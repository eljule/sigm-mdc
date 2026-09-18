<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\LaborCondition;
use App\Models\Subsystem;
use App\Models\Ubigeo;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Seed master data: Document types, Ubigeo, Labor conditions, Subsystems.
     */
    public function run(): void
    {
        // 1. Tipos de Documento
        $documentTypes = [
            ['code' => '01', 'name' => 'DNI', 'length' => 8, 'is_active' => true],
            ['code' => '06', 'name' => 'RUC', 'length' => 11, 'is_active' => true],
        ];

        foreach ($documentTypes as $doc) {
            DocumentType::updateOrCreate(['code' => $doc['code']], $doc);
        }

        // 2. Ubigeo (Castilla, Piura)
        Ubigeo::updateOrCreate(
            ['code' => '200104'],
            [
                'department' => 'PIURA',
                'province' => 'PIURA',
                'district' => 'CASTILLA',
                'is_active' => true,
            ]
        );

        // 3. Condiciones Laborales
        $laborConditions = [
            ['id' => 1, 'code' => 'CAS', 'name' => 'CONTRATO ADMINISTRATIVO DE SERVICIOS (CAS)', 'is_active' => true],
            ['id' => 2, 'code' => 'NOM', 'name' => 'DECRETO LEGISLATIVO 276 (NOMBRADO)', 'is_active' => true],
            ['id' => 3, 'code' => 'casc', 'name' => 'CONTRATO ADMISTRATIVO DE SERVICIOS DE CONFIANZA', 'is_active' => true],
        ];

        foreach ($laborConditions as $condition) {
            LaborCondition::updateOrCreate(['id' => $condition['id']], $condition);
        }

        // 4. Subsistemas
        $subsystems = [
            [
                'code' => 'central',
                'name' => 'Dashboard Central y Configuración',
                'description' => 'Núcleo y launcher del SIGM',
                'icon' => 'heroicon-o-cog-6-tooth',
                'url_path' => '/admin',
                'is_active' => true,
            ],
            [
                'code' => 'transportes',
                'name' => 'Licencias de Transportes',
                'description' => 'Empadronamiento de vehículos, licencias de conducir y registro de mototaxis.',
                'icon' => 'heroicon-o-truck',
                'url_path' => '/admin',
                'is_active' => true,
            ],
            [
                'code' => 'itam',
                'name' => 'Inventario y Gestión TI',
                'description' => 'Gestión de activos de hardware y software (ITAM)',
                'icon' => 'heroicon-o-computer-desktop',
                'url_path' => '/itam',
                'is_active' => true,
            ],
            [
                'code' => 'helpdesk',
                'name' => 'Soporte Técnico y Helpdesk',
                'description' => 'Mesa de partes y resolución de incidencias',
                'icon' => 'heroicon-o-ticket',
                'url_path' => '/helpdesk',
                'is_active' => true,
            ],
        ];

        foreach ($subsystems as $sub) {
            Subsystem::updateOrCreate(['code' => $sub['code']], $sub);
        }
    }
}
