<?php
use App\Models\User;
use App\Models\Role;
use App\Models\Subsystem;

$user = User::where('name', 'like', '%Josue%')->first();
$subsystemItam = Subsystem::where('code', 'itam')->first();
$roleSoporteTi = Role::where('name', 'Soporte TI')->where('subsystem_id', $subsystemItam->id)->first();

if (!$roleSoporteTi) {
    // Si no existe con subsystem_id, buscar por nombre
    $roleSoporteTi = Role::where('name', 'Soporte TI')->first();
}

if ($user && $roleSoporteTi && $subsystemItam) {
    $user->allRoles()->syncWithoutDetaching([
        $roleSoporteTi->id => ['subsystem_id' => $subsystemItam->id]
    ]);
    echo "Rol Soporte TI asignado correctamente a {$user->name} para el subsistema ITAM." . PHP_EOL;
} else {
    echo "No se pudo realizar la asignación. User: " . ($user?->name) . ", Role: " . ($roleSoporteTi?->name) . ", Subsystem: " . ($subsystemItam?->code) . PHP_EOL;
}
