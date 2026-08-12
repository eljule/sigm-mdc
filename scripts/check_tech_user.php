<?php
$techRoles = ['Administrador Central', 'Administrador de TI', 'Técnico de Soporte'];
$users = \App\Models\User::all();
foreach ($users as $u) {
    $roles = $u->allRoles->pluck('name')->toArray();
    $isTech = count(array_intersect($roles, $techRoles)) > 0;
    echo "User {$u->id}: {$u->name} -> isTech? " . ($isTech ? 'SI' : 'NO') . PHP_EOL;
}
