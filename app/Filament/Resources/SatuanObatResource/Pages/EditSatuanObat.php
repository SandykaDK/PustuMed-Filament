<?php

namespace App\Filament\Resources\SatuanObatResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SatuanObatResource;

class EditSatuanObat extends EditRecord
{
    protected static string $resource = SatuanObatResource::class;

    public function getTitle(): string
    {
        return 'Ubah Satuan Obat';
    }

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
            ->body('Data Satuan Obat berhasil disimpan');
    }
}
