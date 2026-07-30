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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
