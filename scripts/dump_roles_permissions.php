<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Subsystem;

echo "=== SUBSYSTEMS ===" . PHP_EOL;
$subsystems = Subsystem::all();
foreach ($subsystems as $s) {
    echo "ID: {$s->id} | Code: {$s->code} | Name: {$s->name}" . PHP_EOL;
}

echo PHP_EOL . "=== ROLES ===" . PHP_EOL;
$roles = Role::all();
foreach ($roles as $r) {
    echo "Role ID: {$r->id} | Name: {$r->name} | Guard: {$r->guard_name}" . PHP_EOL;
}

echo PHP_EOL . "=== PERMISSIONS ===" . PHP_EOL;
$permissions = Permission::all();
foreach ($permissions as $p) {
    echo "Permission ID: {$p->id} | Name: {$p->name} | Guard: {$p->guard_name}" . PHP_EOL;
}

echo PHP_EOL . "=== ROLE HAS PERMISSIONS ===" . PHP_EOL;
$rolePermissions = \DB::table('role_has_permissions')->get();
foreach ($rolePermissions as $rp) {
    $role = $roles->firstWhere('id', $rp->role_id);
    $perm = $permissions->firstWhere('id', $rp->permission_id);
    echo "Role: " . ($role?->name ?? $rp->role_id) . " -> Permission: " . ($perm?->name ?? $rp->permission_id) . PHP_EOL;
}
