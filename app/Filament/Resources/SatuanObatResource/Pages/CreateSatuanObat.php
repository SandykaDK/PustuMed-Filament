<?php

namespace App\Filament\Resources\SatuanObatResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SatuanObatResource;

class CreateSatuanObat extends CreateRecord
{
    protected static string $resource = SatuanObatResource::class;

    public function getTitle(): string
    {
        return 'Tambah Satuan Obat';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Success')
            ->body('Data Satuan Obat berhasil disimpan');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
