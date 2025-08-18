<?php

namespace App\Filament\Resources\LaporanStokResource\Pages;

use App\Filament\Resources\LaporanStokResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporanStok extends ViewRecord
{
    protected static string $resource = LaporanStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
