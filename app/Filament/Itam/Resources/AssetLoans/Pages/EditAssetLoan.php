<?php

namespace App\Filament\Itam\Resources\AssetLoans\Pages;

use App\Filament\Itam\Resources\AssetLoans\AssetLoanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssetLoan extends EditRecord
{
    protected static string $resource = AssetLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
