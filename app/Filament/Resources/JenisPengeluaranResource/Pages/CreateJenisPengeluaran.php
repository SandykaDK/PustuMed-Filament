<?php

namespace App\Filament\Resources\JenisPengeluaranResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\JenisPengeluaranResource;

class CreateJenisPengeluaran extends CreateRecord
{
    protected static string $resource = JenisPengeluaranResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Success')
            ->body('Data Jenis Pengeluaran berhasil disimpan');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
