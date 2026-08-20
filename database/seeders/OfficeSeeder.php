<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Registra y estructura la jerarquía de Oficinas según el Organigrama Estructural
     * de la Municipalidad Distrital de Castilla (Ordenanza Municipal N°022-2020-MDC).
     */
    public function run(): void
    {
        $organigrama = [
            // Alta Dirección
            ['code' => '01', 'acronym' => 'ALC', 'name' => 'Alcaldía', 'parent' => null],
            ['code' => '01.01', 'acronym' => 'OCI', 'name' => 'Órgano de Control Institucional', 'parent' => 'Alcaldía'],
            ['code' => '01.02', 'acronym' => 'PPM', 'name' => 'Procuraduría Pública Municipal', 'parent' => 'Alcaldía'],
            ['code' => '02', 'acronym' => 'GM', 'name' => 'Gerencia Municipal', 'parent' => 'Alcaldía'],

            // Órganos de Asesoramiento y Apoyo (Gerencia Municipal)
            ['code' => '02.01', 'acronym' => 'SG', 'name' => 'Secretaría General', 'parent' => 'Gerencia Municipal'],
            ['code' => '02.01.01', 'acronym' => 'OGDAC', 'name' => 'Oficina de Gestión Documentaria y Atención al Ciudadano', 'parent' => 'Secretaría General'],
            ['code' => '02.01.02', 'acronym' => 'OCSRP', 'name' => 'Oficina de Comunicación Social y Relaciones Públicas', 'parent' => 'Secretaría General'],

            ['code' => '02.02', 'acronym' => 'OGAF', 'name' => 'Oficina General de Administración y Finanzas', 'parent' => 'Gerencia Municipal'],
            ['code' => '02.02.01', 'acronym' => 'ORH', 'name' => 'Oficina de Recursos Humanos', 'parent' => 'Oficina General de Administración y Finanzas'],
            ['code' => '02.02.02', 'acronym' => 'OACP', 'name' => 'Oficina de Abastecimientos y Control Patrimonial', 'parent' => 'Oficina General de Administración y Finanzas'],
            ['code' => '02.02.03', 'acronym' => 'OCC', 'name' => 'Oficina de Contabilidad y Costos', 'parent' => 'Oficina General de Administración y Finanzas'],
            ['code' => '02.02.04', 'acronym' => 'OT', 'name' => 'Oficina de Tesorería', 'parent' => 'Oficina General de Administración y Finanzas'],
            ['code' => '02.02.05', 'acronym' => 'ODT', 'name' => 'Oficina de Desarrollo Tecnológico', 'parent' => 'Oficina General de Administración y Finanzas'],

            ['code' => '02.03', 'acronym' => 'OGAJ', 'name' => 'Oficina General de Asesoría Jurídica', 'parent' => 'Gerencia Municipal'],

            ['code' => '02.04', 'acronym' => 'OGPP', 'name' => 'Oficina General de Planeamiento y Presupuesto', 'parent' => 'Gerencia Municipal'],
            ['code' => '02.04.01', 'acronym' => 'OP', 'name' => 'Oficina de Presupuesto', 'parent' => 'Oficina General de Planeamiento y Presupuesto'],
            ['code' => '02.04.02', 'acronym' => 'OMIE', 'name' => 'Oficina de Modernización Institucional y Estadística', 'parent' => 'Oficina General de Planeamiento y Presupuesto'],

            // Gerencia de Administración Tributaria
            ['code' => '03', 'acronym' => 'GAT', 'name' => 'Gerencia de Administración Tributaria', 'parent' => 'Gerencia Municipal'],
            ['code' => '03.01', 'acronym' => 'SGT', 'name' => 'Subgerencia de Tributación', 'parent' => 'Gerencia de Administración Tributaria'],
            ['code' => '03.02', 'acronym' => 'SGR', 'name' => 'Subgerencia de Recaudación', 'parent' => 'Gerencia de Administración Tributaria'],
            ['code' => '03.03', 'acronym' => 'EC', 'name' => 'Ejecutoría Coactiva', 'parent' => 'Gerencia de Administración Tributaria'],
            ['code' => '03.04', 'acronym' => 'SGLA', 'name' => 'Subgerencia de Licencias y Autorizaciones', 'parent' => 'Gerencia de Administración Tributaria'],
            ['code' => '03.05', 'acronym' => 'SGF', 'name' => 'Subgerencia de Fiscalización', 'parent' => 'Gerencia de Administración Tributaria'],
            ['code' => '03.05.01', 'acronym' => 'UFAPM', 'name' => 'Unidad de Fiscalización Administrativa y Policía Municipal', 'parent' => 'Subgerencia de Fiscalización'],

            // Gerencia de Desarrollo Urbano-Rural e Infraestructura
            ['code' => '04', 'acronym' => 'GDURI', 'name' => 'Gerencia de Desarrollo Urbano-Rural e Infraestructura', 'parent' => 'Gerencia Municipal'],
            ['code' => '04.01', 'acronym' => 'SFPIP', 'name' => 'Subgerencia de Formulación de Proyectos de Inversión Pública', 'parent' => 'Gerencia de Desarrollo Urbano-Rural e Infraestructura'],
            ['code' => '04.02', 'acronym' => 'SEPIP', 'name' => 'Subgerencia de Estudios y Proyectos de Inversión Pública', 'parent' => 'Gerencia de Desarrollo Urbano-Rural e Infraestructura'],
            ['code' => '04.03', 'acronym' => 'SGO', 'name' => 'Subgerencia de Obras', 'parent' => 'Gerencia de Desarrollo Urbano-Rural e Infraestructura'],
            ['code' => '04.04', 'acronym' => 'SGLO', 'name' => 'Subgerencia de Liquidación de Obras', 'parent' => 'Gerencia de Desarrollo Urbano-Rural e Infraestructura'],
            ['code' => '04.05', 'acronym' => 'SGC', 'name' => 'Subgerencia de Catastro', 'parent' => 'Gerencia de Desarrollo Urbano-Rural e Infraestructura'],
            ['code' => '04.06', 'acronym' => 'SGSFL', 'name' => 'Subgerencia de Saneamiento Físico Legal', 'parent' => 'Gerencia de Desarrollo Urbano-Rural e Infraestructura'],
            ['code' => '04.07', 'acronym' => 'SGGRD', 'name' => 'Subgerencia de Gestión del Riesgo de Desastres', 'parent' => 'Gerencia de Desarrollo Urbano-Rural e Infraestructura'],

            // Gerencia de Desarrollo Económico Local
            ['code' => '05', 'acronym' => 'GDEL', 'name' => 'Gerencia de Desarrollo Económico Local', 'parent' => 'Gerencia Municipal'],
            ['code' => '05.01', 'acronym' => 'SGC', 'name' => 'Subgerencia de Comercialización', 'parent' => 'Gerencia de Desarrollo Económico Local'],
            ['code' => '05.02', 'acronym' => 'SGPTEI', 'name' => 'Subgerencia de Promoción Turística, Empresarial e Inversiones', 'parent' => 'Gerencia de Desarrollo Económico Local'],

            // Gerencia de Desarrollo Humano
            ['code' => '06', 'acronym' => 'GDH', 'name' => 'Gerencia de Desarrollo Humano', 'parent' => 'Gerencia Municipal'],
            ['code' => '06.01', 'acronym' => 'SECDR', 'name' => 'Subgerencia de Educación, Cultura, Deporte y Recreación', 'parent' => 'Gerencia de Desarrollo Humano'],
            ['code' => '06.02', 'acronym' => 'SGPC', 'name' => 'Subgerencia de Participación Ciudadana', 'parent' => 'Gerencia de Desarrollo Humano'],
            ['code' => '06.03', 'acronym' => 'SGIS', 'name' => 'Subgerencia de Inclusión Social', 'parent' => 'Gerencia de Desarrollo Humano'],
            ['code' => '06.04', 'acronym' => 'SRCPPS', 'name' => 'Subgerencia de Registro Civil, Población y Promoción de la Salud', 'parent' => 'Gerencia de Desarrollo Humano'],

            // Gerencia de Servicios Públicos
            ['code' => '07', 'acronym' => 'GSP', 'name' => 'Gerencia de Servicios Públicos', 'parent' => 'Gerencia Municipal'],
            ['code' => '07.01', 'acronym' => 'SGGA', 'name' => 'Subgerencia de Gestión Ambiental', 'parent' => 'Gerencia de Servicios Públicos'],
            ['code' => '07.01.01', 'acronym' => 'ULP', 'name' => 'Unidad de Limpieza Pública', 'parent' => 'Subgerencia de Gestión Ambiental'],
            ['code' => '07.01.02', 'acronym' => 'UPJ', 'name' => 'Unidad de Parques y Jardines', 'parent' => 'Subgerencia de Gestión Ambiental'],
            ['code' => '07.02', 'acronym' => 'SGSG', 'name' => 'Subgerencia de Servicios Generales', 'parent' => 'Gerencia de Servicios Públicos'],
            ['code' => '07.02.01', 'acronym' => 'UTMM', 'name' => 'Unidad de Taller de Mecánica y Maestranza', 'parent' => 'Subgerencia de Servicios Generales'],
            ['code' => '07.03', 'acronym' => 'STTV', 'name' => 'Subgerencia de Transporte, Tránsito y Vialidad', 'parent' => 'Gerencia de Servicios Públicos'],

            // Gerencia Seguridad Ciudadana
            ['code' => '08', 'acronym' => 'GSC', 'name' => 'Gerencia Seguridad Ciudadana', 'parent' => 'Gerencia Municipal'],
            ['code' => '08.01', 'acronym' => 'SGS', 'name' => 'Subgerencia de Serenazgo', 'parent' => 'Gerencia Seguridad Ciudadana'],
        ];

        $createdMap = [];

        // 1. Crear o actualizar por nombre / código
        foreach ($organigrama as $item) {
            $name = trim($item['name']);
            
            $office = Office::where('name', 'ILIKE', $name)
                ->orWhere('code', $item['code'])
                ->first();

            if (! $office) {
                $office = Office::create([
                    'code' => $item['code'],
                    'name' => $name,
                    'acronym' => $item['acronym'],
                    'is_active' => true,
                ]);
            } else {
                $office->update([
                    'code' => $item['code'],
                    'name' => $name,
                    'acronym' => $item['acronym'],
                    'is_active' => true,
                ]);
            }
            $createdMap[$name] = $office;
        }

        // 2. Establecer las relaciones parent_id jerárquicas
        foreach ($organigrama as $item) {
            $name = trim($item['name']);
            $parentName = $item['parent'] ? trim($item['parent']) : null;

            if ($parentName && isset($createdMap[$parentName])) {
                $parentId = $createdMap[$parentName]->id;
                $createdMap[$name]->update(['parent_id' => $parentId]);
            } else {
                $createdMap[$name]->update(['parent_id' => null]);
            }
        }
    }
}
