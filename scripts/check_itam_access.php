<?php
// Verificar qué usuarios tienen acceso al subsistema ITAM y con qué roles
$users = \App\Models\User::with('roles')->get();
foreach ($users as $u) {
    $roles = $u->roles->pluck('name')->implode(', ');
    echo "Usuario: {$u->name} | Roles: " . ($roles ?: '(sin rol)') . PHP_EOL;
}

echo PHP_EOL . '=== Acceso al subsistema ITAM ===' . PHP_EOL;
// Buscar el subsystem team para ITAM
$itamTeam = \App\Models\Subsystem::where('slug', 'itam')->first();
if ($itamTeam) {
    echo "Subsistema ITAM encontrado: ID={$itamTeam->id}" . PHP_EOL;
    // Ver usuarios con roles en ese equipo
    $usersWithItam = \App\Models\User::role(
        \Spatie\Permission\Models\Role::all(),
        'web'
    )->get();
    foreach ($usersWithItam as $u) {
        echo "  - {$u->name}" . PHP_EOL;
    }
} else {
    echo "No se encontró modelo Subsystem, verificando teams..." . PHP_EOL;
}
