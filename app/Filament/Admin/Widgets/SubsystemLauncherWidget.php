<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class SubsystemLauncherWidget extends Widget
{
    protected static ?int $sort = 2;
    protected string $view = 'filament.admin.widgets.subsystem-launcher-widget';
    protected int | string | array $columnSpan = 'full';
}
