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

    public function handle(Request $request, Closure $next, ?string $subsystemCode = null)
    {
        \Illuminate\Support\Facades\Log::info('GLOBAL MIDDLEWARE START. Path: ' . $request->path() . ' User authenticated: ' . (auth()->check() ? 'YES' : 'NO'));
        $user = auth()->user();
        if (! $user) {
            return $next($request);
        }

        // Expulsar e invalidar sesión si la cuenta está inactiva o pendiente de aprobación por ODT
        if (! (bool) $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/admin/login')->withErrors([
                'data.username' => 'Tu cuenta ha sido desactivada o se encuentra PENDIENTE DE APROBACIÓN por la Oficina de Desarrollo Tecnológico (ODT).',
            ]);
        }

        $path = $request->path();
        $referer = $request->header('referer', '');

        // Excluir rutas públicas de inicio de sesión y registro del control de subsistema
        if (preg_match('#^(login|register|admin/login|admin/register|helpdesk/login|itam/login)(/|$)#i', $path)) {
            return $next($request);
        }

        // Determinar si la petición es para un panel administrativo de Filament
        $isPanelRequest = false;
        if (preg_match('#^(helpdesk|itam|admin)(/|$)#', $path) ||
            preg_match('#/(helpdesk|itam|admin)(/|$)#', $referer)) {
            $isPanelRequest = true;
        }

        // Si no se provee el código, lo resolvemos dinámicamente
        if (empty($subsystemCode)) {
            $panel = \Filament\Facades\Filament::getCurrentPanel();
            if ($panel) {
                $panelId = $panel->getId();
                $subsystemCode = $panelId === 'admin' ? 'central' : $panelId;
            }
        }

        // Fallback por path o referer
        if (empty($subsystemCode)) {
            if (str_contains($path, 'helpdesk') || str_contains($referer, 'helpdesk') || str_contains($path, 'soporte') || str_contains($referer, 'soporte')) {
                $subsystemCode = 'helpdesk';
            } elseif (str_contains($path, 'itam') || str_contains($referer, 'itam')) {
                $subsystemCode = 'itam';
            } elseif (str_contains($path, 'admin') || str_contains($referer, 'admin')) {
                $subsystemCode = 'central';
            }
        }

        if (empty($subsystemCode)) {
            // Si no se detecta subsistema y no es una ruta de panel, continuamos sin modificar el team_id
            return $next($request);
        }

        // Buscar el subsistema por su código
        $subsystem = Subsystem::where('code', $subsystemCode)->first();
        if (! $subsystem || ! $subsystem->is_active) {
            if ($isPanelRequest) {
                abort(403, 'Subsistema no encontrado o inactivo.');
            }
            return $next($request);
        }

        $subsystemId = $subsystem->id;

        // Establecer el ID de equipo de Spatie para escopar roles y permisos
        app(PermissionRegistrar::class)->setPermissionsTeamId($subsystemId);

        // Verificar acceso al panel: solo se restringe si la petición es explícitamente para el panel Filament
        if ($isPanelRequest) {
            $hasAccess = $user->allRoles()->where('model_has_roles.subsystem_id', $subsystemId)->exists();
            if (! $hasAccess) {
                return redirect('/')->with('error', 'No tienes permisos para acceder al subsistema seleccionado.');
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
        }

        return $next($request);
    }
}
