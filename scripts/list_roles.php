<?php
$roles = \Spatie\Permission\Models\Role::with('users')->get();
foreach ($roles as $r) {
    $names = $r->users->pluck('name')->implode(', ');
    echo $r->name . ': ' . ($names ?: '(ninguno)') . PHP_EOL;
}
echo PHP_EOL . '=== Permisos por rol ===' . PHP_EOL;
foreach ($roles as $r) {
    echo PHP_EOL . $r->name . ':' . PHP_EOL;
    foreach ($r->permissions as $p) {
        echo '  - ' . $p->name . PHP_EOL;
    }
}
