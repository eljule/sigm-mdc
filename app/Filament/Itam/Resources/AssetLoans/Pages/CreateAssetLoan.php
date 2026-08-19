<?php

namespace App\Filament\Itam\Resources\AssetLoans\Pages;

use App\Filament\Itam\Resources\AssetLoans\AssetLoanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetLoan extends CreateRecord
{
    protected static string $resource = AssetLoanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        // Validar solapamiento de fechas para el mismo equipo
        $assetId = $data['asset_id'];
        $start = $data['start_time'];
        $end = $data['end_time'];

        $overlap = \App\Models\AssetLoan::where('asset_id', $assetId)
            ->whereIn('status', ['pending', 'active'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function ($sub) use ($start, $end) {
                      $sub->where('start_time', '<=', $start)
                          ->where('end_time', '>=', $end);
                  });
            })
            ->first();

        if ($overlap) {
            \Filament\Notifications\Notification::make()
                ->title('Conflicto de Horario')
                ->body("El equipo ya tiene una reserva activa/pendiente en ese rango de horario (Reserva {$overlap->loan_number}).")
                ->danger()
                ->send();

            $this->halt();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
