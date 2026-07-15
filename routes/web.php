<?php

use App\Models\Subsystem;
use Illuminate\Support\Facades\Route;

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
