<?php

namespace App\Filament\Resources\SatuanObatResource\Pages;

use App\Filament\Resources\SatuanObatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSatuanObat extends CreateRecord
{
    protected static string $resource = SatuanObatResource::class;
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data Satuan Obat berhasil disimpan';
    }
}
