<?php

use App\Models\Subsystem;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $subsystems = Subsystem::where('is_active', true)->orderBy('id')->get();

    return view('welcome', compact('subsystems'));
});
