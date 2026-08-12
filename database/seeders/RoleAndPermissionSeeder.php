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
                'eliminar-tickets',
                'modificar-tickets',
                'insertar-tickets',
                'insertar-categorias',
                'modificar-categorias',
                'eliminar-categorias',
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

        // 4. Asignar Permisos a Roles
        $rolePermissionsMap = [
            'Administrador de Helpdesk' => [
                'eliminar-tickets',
                'modificar-tickets',
                'insertar-categorias',
                'modificar-categorias',
                'eliminar-categorias',
            ],
            'Técnico de Soporte' => [
                'modificar-tickets',
            ],
            'Usuario Reportante' => [
                'insertar-tickets',
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
