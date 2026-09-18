<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Subsystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Super-Admin Bypass: Administrador Central aprueba implícitamente todos los permisos transversales
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            // 1. Si el usuario es Administrador Central (superadmin transversal del municipio)
            if ($user->allRoles()->where('roles.name', 'Administrador Central')->exists()) {
                return true;
            }

            // 2. Si el usuario tiene rol de Administrador en el subsistema activo
            $currentSubsystemId = app(\Spatie\Permission\PermissionRegistrar::class)->getPermissionsTeamId();
            if ($currentSubsystemId) {
                $hasAdminRole = $user->allRoles()
                    ->where('model_has_roles.subsystem_id', $currentSubsystemId)
                    ->whereIn('roles.name', [
                        'Administrador Central',
                        'Administrador de Helpdesk',
                        'Administrador de TI',
                        'Admin-Soporte',
                        'admin-soporte',
                    ])
                    ->exists();

                if ($hasAdminRole) {
                    return true;
                }
            }

            return null;
        });

        Event::listen(ServingFilament::class, function () {
            $panel = Filament::getCurrentPanel();
            if ($panel) {
                $panelId = $panel->getId();
                $subsystemCode = $panelId === 'admin' ? 'central' : $panelId;

                $subsystem = Subsystem::where('code', $subsystemCode)->first();
                if ($subsystem) {
                    app(PermissionRegistrar::class)->setPermissionsTeamId($subsystem->id);
                }
            }
        });
    }

}
