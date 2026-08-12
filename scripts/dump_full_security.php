<?php
echo "=== ROLES TABLE ===" . PHP_EOL;
$roles = DB::table('roles')->get();
foreach ($roles as $r) {
    echo "id: {$r->id} | name: {$r->name} | subsystem_id: {$r->subsystem_id} | guard_name: {$r->guard_name}" . PHP_EOL;
}

echo PHP_EOL . "=== PERMISSIONS TABLE ===" . PHP_EOL;
$permissions = DB::table('permissions')->get();
foreach ($permissions as $p) {
    echo "id: {$p->id} | name: {$p->name} | subsystem_id: {$p->subsystem_id} | guard_name: {$p->guard_name}" . PHP_EOL;
}

echo PHP_EOL . "=== ROLE_HAS_PERMISSIONS ===" . PHP_EOL;
$rhp = DB::table('role_has_permissions')->get();
foreach ($rhp as $item) {
    $r = $roles->firstWhere('id', $item->role_id);
    $p = $permissions->firstWhere('id', $item->permission_id);
    echo "Role: [{$r->name}] (id:{$r->id}) ---> Permission: [{$p->name}] (id:{$p->id})" . PHP_EOL;
}

echo PHP_EOL . "=== MODEL_HAS_ROLES ===" . PHP_EOL;
$mhr = DB::table('model_has_roles')->get();
foreach ($mhr as $item) {
    $r = $roles->firstWhere('id', $item->role_id);
    $u = DB::table('users')->where('id', $item->model_id)->first();
    echo "User: [{$u?->name}] (id:{$item->model_id}) ---> Role: [{$r?->name}] (subsystem_id: {$item->subsystem_id})" . PHP_EOL;
}
