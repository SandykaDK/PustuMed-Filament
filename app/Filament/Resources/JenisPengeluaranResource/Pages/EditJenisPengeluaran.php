<?php

namespace App\Filament\Resources\JenisPengeluaranResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\JenisPengeluaranResource;

class EditJenisPengeluaran extends EditRecord
{
    protected static string $resource = JenisPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Success')
            ->body('Data Jenis Pengeluaran berhasil disimpan');
    }
}
