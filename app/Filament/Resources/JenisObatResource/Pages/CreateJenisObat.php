<?php

namespace App\Filament\Resources\JenisObatResource\Pages;

use App\Filament\Resources\JenisObatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisObat extends CreateRecord
{
    protected static string $resource = JenisObatResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data Jenis Obat berhasil disimpan';
    }
}
