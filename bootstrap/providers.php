<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\HelpdeskPanelProvider;
use App\Providers\Filament\ItamPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    HelpdeskPanelProvider::class,
    ItamPanelProvider::class,
];
