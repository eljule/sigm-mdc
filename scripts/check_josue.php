<?php
$user = \App\Models\User::where('name', 'like', '%Josue%')->first();
if (!$user) {
    echo "Usuario Josue no encontrado" . PHP_EOL;
    exit;
}

echo "=== USUARIO ===" . PHP_EOL;
echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email}" . PHP_EOL;

echo PHP_EOL . "=== ROLES ASIGNADOS (allRoles) ===" . PHP_EOL;
$roles = $user->allRoles;
foreach ($roles as $r) {
    echo "Role: {$r->name} | Subsystem ID: {$r->pivot->subsystem_id}" . PHP_EOL;
}

echo PHP_EOL . "=== PERMISOS EFECTIVOS EN ITAM (subsystem_id = 3) ===" . PHP_EOL;
app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(3);
$permissions = $user->getAllPermissions();
foreach ($permissions as $p) {
    echo "  - Permission: {$p->name}" . PHP_EOL;
}
