<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetCategories\Pages;

use App\Filament\Itam\Resources\AssetCategories\AssetCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetCategory extends CreateRecord
{
    protected static string $resource = AssetCategoryResource::class;
}
