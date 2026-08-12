<?php
$users = \App\Models\User::all();
foreach ($users as $u) {
    echo "User ID {$u->id}: {$u->name} ({$u->username})" . PHP_EOL;
    $allRoles = $u->allRoles->pluck('name')->unique()->implode(', ');
    echo "  Roles: " . ($allRoles ?: 'ninguno') . PHP_EOL;
}
