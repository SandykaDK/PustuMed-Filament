<?php

namespace App\Filament\Resources\PenerimaanObatResource\Pages;

use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PenerimaanObatResource;

class CreatePenerimaanObat extends CreateRecord
{
    protected static string $resource = PenerimaanObatResource::class;

    protected function afterCreate(): void
    {
        $this->calculateMinMaxStock();
    }

    protected function calculateMinMaxStock(): void
    {
        $penerimaan = $this->record;

        if (!$penerimaan->detailPenerimaanObat) {
            return;
        }

        foreach ($penerimaan->detailPenerimaanObat as $detail) {
            $namaObat = $detail->namaObat;

            if (!$namaObat) {
                continue;
            }

            // Ambil data penjualan/pengeluaran untuk perhitungan
            $rataRataPengeluaran = $this->getRataRataPengeluaran($namaObat->id);
            $leadTime = $namaObat->lead_time ?? 7;
            $safetyFactor = 1.5;

            // Perhitungan Safety Stock
            $safetyStock = (int) ceil($rataRataPengeluaran * $safetyFactor);

            // Perhitungan Reorder Point (ROP)
            $reorderPoint = (int) ceil(($rataRataPengeluaran * $leadTime) + $safetyStock);

            // Perhitungan Minimum Stock
            $minimumStock = $reorderPoint;

            // Perhitungan Maximum Stock
            $maximumStock = (int) ceil($reorderPoint * 3);

            // Update atau create di tabel min_max
            $namaObat->minMax()->updateOrCreate(
                ['nama_obat_id' => $namaObat->id],
                [
                    'minimum_stock' => $minimumStock,
                    'maximum_stock' => $maximumStock,
                    'safety_stock' => $safetyStock,
                    'reorder_point' => $reorderPoint,
                    'lead_time' => $leadTime,
                ]
            );
        }
    }

    protected function getRataRataPengeluaran($namaObatId): float
    {
        // Ambil rata-rata pengeluaran 30 hari terakhir
        $rataRata = DB::table('detail_pengeluaran_obat')
            ->join('pengeluaran_obat', 'detail_pengeluaran_obat.pengeluaran_obat_id', '=', 'pengeluaran_obat.id')
            ->where('detail_pengeluaran_obat.nama_obat_id', $namaObatId)
            ->where('pengeluaran_obat.tanggal_pengeluaran', '>=', now()->subDays(30))
            ->avg('detail_pengeluaran_obat.jumlah_keluar');

        return $rataRata ?? 0; // Default 0 jika belum ada data
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function getCreatedNotificationTitle(): ?string
    // {
    //     return 'Data Penerimaan Obat berhasil disimpan';
    // }
}
