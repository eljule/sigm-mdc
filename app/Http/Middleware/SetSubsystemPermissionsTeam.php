<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Subsystem;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class SetSubsystemPermissionsTeam
{
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

        return $next($request);
    }
}
