<?php

namespace App\Filament\Resources\NamaObatResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\NamaObatResource;
use App\Filament\Resources\NamaObatResource\Widgets\DetailObatTable;

class EditNamaObat extends EditRecord
{
    protected static string $resource = NamaObatResource::class;

    public function getTitle(): string
    {
        return 'Ubah Daftar Obat';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            //
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            //
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Success')
            ->body('Data Nama Obat berhasil disimpan');
    }
}
