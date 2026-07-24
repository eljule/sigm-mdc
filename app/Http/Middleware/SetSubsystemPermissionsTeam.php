<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Subsystem;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class SetSubsystemPermissionsTeam
{
    /**
     * Roles que solo pueden usar el portal /soporte y NO el panel administrativo de Helpdesk.
     * Agrega aquí cualquier rol "solo-portal" adicional que se cree en el futuro.
     */
    protected const PORTAL_ONLY_ROLES = [
        'Usuario Reportante',
    ];

    public function handle(Request $request, Closure $next, string $subsystemCode)
    {
        $user = auth()->user();
        if (! $user) {
            return $next($request);
        }

        // Buscar el subsistema por su código
        $subsystem = Subsystem::where('code', $subsystemCode)->first();
        if (! $subsystem || ! $subsystem->is_active) {
            abort(403, 'Subsistema no encontrado o inactivo.');
        }

        $subsystemId = $subsystem->id;

        // Establecer el ID de equipo de Spatie para escopar roles y permisos
        app(PermissionRegistrar::class)->setPermissionsTeamId($subsystemId);

        // Verificar acceso: el usuario debe tener al menos un rol asignado en este subsistema
        $hasAccess = $user->allRoles()->where('model_has_roles.subsystem_id', $subsystemId)->exists();

        if (! $hasAccess) {
            abort(403, 'No tienes permisos para acceder a este subsistema.');
        }

        // Verificar que no sea un rol de solo-portal (ej: Usuario Reportante en Helpdesk).
        // Estos usuarios deben usar /soporte en lugar del panel administrativo de Filament.
        if ($subsystemCode === 'helpdesk') {
            $userRolesInSubsystem = $user->allRoles()
                ->where('model_has_roles.subsystem_id', $subsystemId)
                ->pluck('name')
                ->toArray();

            $hasOperativeRole = ! empty(array_diff($userRolesInSubsystem, self::PORTAL_ONLY_ROLES));

            if (! $hasOperativeRole) {
                return redirect('/soporte');
            }
        }

        return $next($request);
    }
}
