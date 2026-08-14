<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Subsystem;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Genera y sincroniza todos los roles y permisos definidos por subsistema en el SIGM-MDC.
     */
    public function run(): void
    {
        // Limpiar la caché de permisos de Spatie
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1. Obtener los subsistemas
        $subsystems = Subsystem::all()->keyBy('code');

        $centralId  = $subsystems->get('central')?->id;
        $rentasId   = $subsystems->get('rentas')?->id;
        $itamId     = $subsystems->get('itam')?->id;
        $helpdeskId = $subsystems->get('helpdesk')?->id;

        // 2. Definir Permisos por Subsistema
        $permissionsData = [
            'helpdesk' => [
                // Tickets
                'consultar-tickets',
                'insertar-tickets',
                'modificar-tickets',
                'eliminar-tickets',
                // Categorías de Tickets
                'consultar-categorias',
                'insertar-categorias',
                'modificar-categorias',
                'eliminar-categorias',
                // Base de Conocimientos
                'consultar-kb',
                'insertar-kb',
                'modificar-kb',
                'eliminar-kb',
            ],
            'itam' => [
                // Activos
                'consultar-activos',
                'insertar-activos',
                'modificar-activos',
                'eliminar-activos',
                'dictaminar-baja-activos',
                // Asignaciones
                'consultar-asignaciones',
                'insertar-asignaciones',
                'modificar-asignaciones',
                'eliminar-asignaciones',
                // Mantenimientos
                'consultar-mantenimientos',
                'insertar-mantenimientos',
                'modificar-mantenimientos',
                'eliminar-mantenimientos',
                // Catálogos TI (Marcas, Modelos, Categorías, Software, Consumibles)
                'consultar-catalogos-ti',
                'gestionar-catalogos-ti',
            ],
        ];

        foreach ($permissionsData as $subCode => $perms) {
            $subId = $subsystems->get($subCode)?->id;
            foreach ($perms as $permName) {
                Permission::firstOrCreate(
                    ['name' => $permName, 'guard_name' => 'web'],
                    ['subsystem_id' => $subId]
                );
            }
        }

        // 3. Definir Roles por Subsistema
        $rolesData = [
            ['name' => 'Administrador Central', 'subsystem_id' => $centralId],
            ['name' => 'Operador de Rentas', 'subsystem_id' => $rentasId],
            ['name' => 'Administrador de TI', 'subsystem_id' => $itamId],
            ['name' => 'Soporte TI', 'subsystem_id' => $itamId],
            ['name' => 'Administrador de Helpdesk', 'subsystem_id' => $helpdeskId],
            ['name' => 'Técnico de Soporte', 'subsystem_id' => $helpdeskId],
            ['name' => 'Usuario Reportante', 'subsystem_id' => $helpdeskId],
        ];

        $createdRoles = [];
        foreach ($rolesData as $rData) {
            $role = Role::firstOrCreate(
                ['name' => $rData['name'], 'guard_name' => 'web'],
                ['subsystem_id' => $rData['subsystem_id']]
            );
            $role->update(['subsystem_id' => $rData['subsystem_id']]);
            $createdRoles[$rData['name']] = $role;
        }

        // 4. Asignar Permisos a Roles por Subsistema
        $rolePermissionsMap = [
            'Administrador de Helpdesk' => [
                'consultar-tickets', 'insertar-tickets', 'modificar-tickets', 'eliminar-tickets',
                'consultar-categorias', 'insertar-categorias', 'modificar-categorias', 'eliminar-categorias',
                'consultar-kb', 'insertar-kb', 'modificar-kb', 'eliminar-kb',
            ],
            'Técnico de Soporte' => [
                'consultar-tickets', 'insertar-tickets', 'modificar-tickets',
                'consultar-categorias',
                'consultar-kb', 'insertar-kb', 'modificar-kb',
            ],
            'Usuario Reportante' => [
                'consultar-tickets', 'insertar-tickets',
            ],
            'Administrador de TI' => [
                'consultar-activos', 'insertar-activos', 'modificar-activos', 'eliminar-activos', 'dictaminar-baja-activos',
                'consultar-asignaciones', 'insertar-asignaciones', 'modificar-asignaciones', 'eliminar-asignaciones',
                'consultar-mantenimientos', 'insertar-mantenimientos', 'modificar-mantenimientos', 'eliminar-mantenimientos',
                'consultar-catalogos-ti', 'gestionar-catalogos-ti',
            ],
            'Soporte TI' => [
                'consultar-activos', 'insertar-activos', 'modificar-activos', 'dictaminar-baja-activos',
                'consultar-asignaciones', 'insertar-asignaciones', 'modificar-asignaciones',
                'consultar-mantenimientos', 'insertar-mantenimientos', 'modificar-mantenimientos',
                'consultar-catalogos-ti',
            ],
        ];

        foreach ($rolePermissionsMap as $roleName => $permNames) {
            if (isset($createdRoles[$roleName])) {
                $role = $createdRoles[$roleName];
                if ($role->subsystem_id) {
                    app(PermissionRegistrar::class)->setPermissionsTeamId($role->subsystem_id);
                }
                $role->syncPermissions($permNames);
            }
        }


        // Restablecer el team context
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);
    }
}
