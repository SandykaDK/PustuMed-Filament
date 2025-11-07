<?php

namespace App\Filament\Resources\SatuanObatResource\Pages;

use App\Filament\Resources\SatuanObatResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSatuanObat extends CreateRecord
{
    protected static string $resource = SatuanObatResource::class;
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data Satuan Obat berhasil disimpan';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
