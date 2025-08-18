<?php

namespace App\Filament\Resources\SupplierObatResource\Pages;

use App\Filament\Resources\SupplierObatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplierObat extends CreateRecord
{
    protected static string $resource = SupplierObatResource::class;
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data Supplier berhasil disimpan';
    }
}
