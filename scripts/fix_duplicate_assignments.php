<?php
// Script para identificar y limpiar asignaciones activas duplicadas
// Ejecutar con: sail artisan tinker --execute="require '/var/www/html/scripts/fix_duplicates.php';"

use App\Models\AssetAssignment;
use App\Models\Asset;

echo "=== Asignaciones activas duplicadas ===\n";

$rows = \DB::select("
    SELECT aa.id, aa.asset_id, aa.user_id, aa.assigned_at, a.computer_code
    FROM asset_assignments aa
    JOIN assets a ON a.id = aa.asset_id
    WHERE aa.returned_at IS NULL
    ORDER BY aa.asset_id, aa.assigned_at
");

$byAsset = [];
foreach ($rows as $r) {
    $byAsset[$r->asset_id][] = $r;
}

$duplicates = array_filter($byAsset, fn($g) => count($g) > 1);

if (empty($duplicates)) {
    echo "No hay duplicados.\n";
    exit;
}

foreach ($duplicates as $assetId => $group) {
    $code = $group[0]->computer_code;
    echo "\nActivo [{$code}] tiene " . count($group) . " asignaciones activas:\n";
    foreach ($group as $a) {
        echo "  ID:{$a->id} | user_id:{$a->user_id} | assigned_at:{$a->assigned_at}\n";
    }
    // Conservar la más reciente, cerrar las anteriores
    usort($group, fn($a, $b) => strtotime($b->assigned_at) - strtotime($a->assigned_at));
    $keep = $group[0];
    echo "  -> Conservando ID:{$keep->id} (más reciente)\n";
    foreach (array_slice($group, 1) as $old) {
        echo "  -> Cerrando ID:{$old->id} (returned_at = now)\n";
        \DB::update("UPDATE asset_assignments SET returned_at = NOW(), notes = COALESCE(notes,'') || ' [Cerrado automáticamente: asignación duplicada detectada]' WHERE id = ?", [$old->id]);
    }
}
echo "\nListo. Vuelve a correr la migración.\n";
