<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database in strict dependency order.
     */
    public function run(): void
    {
        // 1. Datos Maestros (Tipos de Documento, Ubigeos, Condiciones Laborales, Subsistemas)
        $this->call(MasterDataSeeder::class);

        // 2. Estructura Orgánica de Oficinas Municipales
        $this->call(OfficeSeeder::class);

        // 3. Roles y Permisos por Subsistema
        $this->call(RoleAndPermissionSeeder::class);

        // 4. Fichas de Personal de RRHH, Usuarios del Sistema y Asignación de Roles
        $this->call(PersonalAndUserSeeder::class);

        // 5. Catálogo ITAM (Categorías, Marcas, Modelos, Bloques y Características Dinámicas)
        $this->call(ItamCatalogSeeder::class);

        // 6. Catálogo de Software Institucional
        $this->call(SoftwareSeeder::class);

        // 7. Activos de Hardware, Valores de Características Técnicas e Instalaciones de Software
        $this->call(AssetSeeder::class);

        // 8. Consumibles e Insumos TI
        $this->call(ConsumableSeeder::class);

        // 9. Mesa de Ayuda y Helpdesk (Categorías de Tickets e Incidencias Demo)
        $this->call(HelpdeskSeeder::class);
    }
}
