<?php

namespace App\Filament\Resources\NamaObatResource\Pages;

use App\Models\LaporanStok;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\NamaObatResource;

class CreateNamaObat extends CreateRecord
{
    protected static string $resource = NamaObatResource::class;
    protected function afterCreate()
    {
        LaporanStok::create([
            'nama_obat_id' => $this->record->id,
            'stok_akhir' => 0,
            'stok_awal' => 0,
            'jumlah_masuk' => 0,
            'jumlah_keluar' => 0,
            'lokasi_penyimpanan' => $this->record->lokasi_penyimpanan ?? '',
            'tanggal_kadaluwarsa_terdekat' => null,
            'status_stok' => 'Habis',
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Success')
            ->body('Data Nama Obat berhasil disimpan');
    }
}
