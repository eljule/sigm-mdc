<?php

use App\Models\Subsystem;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelpdeskPortalController;

Route::get('/', function () {
    $subsystems = Subsystem::where('is_active', true)->orderBy('id')->get();

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
Route::get('/login', function () {
    session(['url.intended' => url()->previous()]);
    return redirect('/admin/login');
})->name('login');

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
});
