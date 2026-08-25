<?php

use App\Models\Subsystem;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelpdeskPortalController;

Route::get('/', function () {
    $allSubsystems = Subsystem::where('is_active', true)->orderBy('id')->get();

    if (auth()->check()) {
        $user = auth()->user();

        $subsystems = $allSubsystems->filter(function ($subsystem) use ($user) {
            // Super-Admin ve todos los subsistemas activos
            if ($user->hasRole('Administrador Central')) {
                return true;
            }

            // Administrador de TI ve Central, ITAM y Helpdesk
            if ($user->hasRole('Administrador de TI') && in_array($subsystem->code, ['central', 'itam', 'helpdesk'])) {
                return true;
            }

            // Verificar si el usuario tiene algún rol asignado en la tabla pivot para este subsistema
            $hasRoleInSubsystem = \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('subsystem_id', $subsystem->id)
                ->exists();

            if ($hasRoleInSubsystem) {
                return true;
            }

            // Todo usuario autenticado municipal tiene acceso al Portal de Soporte Helpdesk (/soporte)
            if ($subsystem->code === 'helpdesk') {
                return true;
            }

            return false;
        });
    } else {
        $subsystems = $allSubsystems;
    }

    return view('welcome', compact('subsystems'));
});

Route::get('/scan/activo/{computer_code}', function (string $computerCode) {
    $asset = \App\Models\Asset::with(['parent', 'components', 'assignments.user', 'assignments.office', 'softwares'])
        ->where('computer_code', $computerCode)
        ->firstOrFail();

    return view('scan.asset', compact('asset'));
});

// Ruta nombrada 'login' requerida por el middleware auth de Laravel.
// Redirige al login del panel Admin de Filament, guardando la URL de destino para retornar tras autenticarse.
// Rutas canónicas del sistema para inicio de sesión y registro público
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/register', function () {
    return redirect('/admin/register');
})->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/soporte', [HelpdeskPortalController::class, 'index'])->name('helpdesk.portal');
    Route::post('/soporte', [HelpdeskPortalController::class, 'store'])->name('helpdesk.portal.store');
    Route::post('/soporte/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('helpdesk.portal.logout');

    // Fichas de reporte e impresión
    Route::get('/fichas/asignacion/{id}', [\App\Http\Controllers\FichaController::class, 'asignacion'])->name('fichas.asignacion');
    Route::get('/fichas/componente/{id}', [\App\Http\Controllers\FichaController::class, 'componente'])->name('fichas.componente');
    Route::get('/fichas/ticket/{id}', [\App\Http\Controllers\FichaController::class, 'ticket'])->name('fichas.ticket');
    Route::get('/fichas/mantenimiento/{id}', [\App\Http\Controllers\FichaController::class, 'mantenimiento'])->name('fichas.mantenimiento');
    Route::get('/fichas/baja/{id}', [\App\Http\Controllers\FichaController::class, 'baja'])->name('fichas.baja');
    Route::get('/fichas/entrega-consumibles/{id}', [\App\Http\Controllers\FichaController::class, 'entregaConsumibles'])->name('fichas.entrega_consumibles');
    Route::get('/fichas/prestamo-equipo/{id}', [\App\Http\Controllers\FichaController::class, 'prestamoEquipo'])->name('fichas.prestamo_equipo');
});

