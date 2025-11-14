<?php

namespace App\Filament\Resources\NamaObatResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\NamaObatResource;
use App\Filament\Resources\NamaObatResource\Widgets\PenerimaanObatChart;
use App\Filament\Resources\NamaObatResource\Widgets\PengeluaranObatChart;
use App\Filament\Resources\NamaObatResource\Widgets\HistoryPenerimaanObat;
use App\Filament\Resources\NamaObatResource\Widgets\HistoryPengeluaranObat;

class EditNamaObat extends EditRecord
{
    protected static string $resource = NamaObatResource::class;

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
            HistoryPenerimaanObat::class,
            HistoryPengeluaranObat::class,
            PenerimaanObatChart::class,
            PengeluaranObatChart::class,
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
