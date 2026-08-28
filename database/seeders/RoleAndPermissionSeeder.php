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
            'central' => [
                // Usuarios
                'consultar-usuarios', 'insertar-usuarios', 'modificar-usuarios', 'eliminar-usuarios',
                // Roles
                'consultar-roles', 'insertar-roles', 'modificar-roles', 'eliminar-roles',
                // Permisos
                'consultar-permisos', 'insertar-permisos', 'modificar-permisos', 'eliminar-permisos',
                // Subsistemas
                'consultar-subsistemas', 'insertar-subsistemas', 'modificar-subsistemas', 'eliminar-subsistemas',
                // Oficinas
                'consultar-oficinas', 'insertar-oficinas', 'modificar-oficinas', 'eliminar-oficinas',
                // Parámetros Maestros
                'consultar-tipos-documento', 'gestionar-tipos-documento',
                'consultar-condiciones-laborales', 'gestionar-condiciones-laborales',
                'consultar-ubigeos', 'gestionar-ubigeos',
            ],
            'itam' => [
                // Activos
                'consultar-activos', 'insertar-activos', 'modificar-activos', 'eliminar-activos', 'dictaminar-baja-activos',
                // Asignaciones
                'consultar-asignaciones', 'insertar-asignaciones', 'modificar-asignaciones', 'eliminar-asignaciones',
                // Mantenimientos
                'consultar-mantenimientos', 'insertar-mantenimientos', 'modificar-mantenimientos', 'eliminar-mantenimientos',
                // Préstamos y Reservas
                'consultar-prestamos', 'insertar-prestamos', 'modificar-prestamos', 'eliminar-prestamos',
                // Insumos y Consumibles
                'consultar-consumibles', 'insertar-consumibles', 'modificar-consumibles', 'eliminar-consumibles', 'despachar-consumibles',
                // Actas de Entrega
                'consultar-actas-entrega', 'reimprimir-actas-entrega',
                // Software
                'consultar-software', 'gestionar-software',
                // Catálogos TI
                'consultar-catalogos-ti', 'gestionar-catalogos-ti',
            ],
            'helpdesk' => [
                // Tickets de Soporte
                'consultar-tickets', 'insertar-tickets', 'modificar-tickets', 'eliminar-tickets', 'atender-tickets', 'cerrar-tickets',
                // Monitoreo Técnico
                'monitorear-tecnicos',
                // Categorías de Tickets
                'consultar-categorias-tickets', 'gestionar-categorias-tickets',
                // Base de Conocimientos
                'consultar-base-conocimiento', 'gestionar-base-conocimiento',
            ],
            'rentas' => [
                // Declaraciones Juradas
                'consultar-declaraciones', 'insertar-declaraciones', 'modificar-declaraciones',
                // Estado de Cuenta
                'consultar-estado-cuenta', 'imprimir-estado-cuenta',
                // Arbitrios
                'consultar-arbitrios', 'gestionar-arbitrios',
            ],
        ];

        foreach ($permissionsData as $subCode => $perms) {
            $subId = $subsystems->get($subCode)?->id;
            if (! $subId) {
                continue;
            }
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
            //['name' => 'Soporte TI', 'subsystem_id' => $itamId],
            ['name' => 'Tecnico de Soporte', 'subsystem_id' => $helpdeskId],
            ['name' => 'Técnico de Soporte', 'subsystem_id' => $helpdeskId],
            ['name' => 'Administrador de Helpdesk', 'subsystem_id' => $helpdeskId],
            ['name' => 'Usuario Reportante', 'subsystem_id' => $helpdeskId],
        ];

        $createdRoles = [];
        foreach ($rolesData as $rData) {
            if (! $rData['subsystem_id']) {
                continue;
            }
            $role = Role::firstOrCreate(
                ['name' => $rData['name'], 'guard_name' => 'web', 'subsystem_id' => $rData['subsystem_id']]
            );
            $role->update(['subsystem_id' => $rData['subsystem_id']]);
            $createdRoles[$rData['name'] . '_' . $rData['subsystem_id']] = $role;
        }

        // 4. Asignar Permisos a Roles por Subsistema
        $rolePermissionsMap = [
            'Administrador Central_' . $centralId => $permissionsData['central'],
            'Administrador de TI_' . $itamId => $permissionsData['itam'],
            'Soporte TI_' . $itamId => [
                'consultar-activos', 'insertar-activos', 'modificar-activos', 'dictaminar-baja-activos',
                'consultar-asignaciones', 'insertar-asignaciones', 'modificar-asignaciones',
                'consultar-mantenimientos', 'insertar-mantenimientos', 'modificar-mantenimientos',
                'consultar-prestamos', 'insertar-prestamos', 'modificar-prestamos',
                'consultar-consumibles', 'despachar-consumibles',
                'consultar-actas-entrega', 'reimprimir-actas-entrega',
                'consultar-catalogos-ti',
            ],
            'Tecnico de Soporte_' . $itamId => [
                'consultar-mantenimientos', 'insertar-mantenimientos', 'modificar-mantenimientos',
            ],
            'Administrador de Helpdesk_' . $helpdeskId => $permissionsData['helpdesk'],
            'Técnico de Soporte_' . $helpdeskId => [
                'consultar-tickets', 'insertar-tickets', 'modificar-tickets', 'atender-tickets',
                'monitorear-tecnicos',
                'consultar-categorias-tickets',
                'consultar-base-conocimiento',
            ],
            'Usuario Reportante_' . $helpdeskId => [
                'consultar-tickets', 'insertar-tickets',
            ],
            'Operador de Rentas_' . $rentasId => $permissionsData['rentas'],
        ];

        foreach ($rolePermissionsMap as $roleKey => $permNames) {
            if (isset($createdRoles[$roleKey])) {
                $role = $createdRoles[$roleKey];
                if ($role->subsystem_id) {
                    app(PermissionRegistrar::class)->setPermissionsTeamId($role->subsystem_id);
                }
                $role->syncPermissions($permNames);
            }
        }

        // Restablecer el team context
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
