<?php

namespace App\Filament\Resources\PengeluaranObatResource\Pages;

use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PengeluaranObatResource;

class CreatePengeluaranObat extends CreateRecord
{
    protected static string $resource = PengeluaranObatResource::class;

    protected function afterCreate(): void
    {
        $this->calculateMinMaxStock();
    }

    protected function calculateMinMaxStock(): void
    {
        $pengeluaran = $this->record;

        if (!$pengeluaran->detailPengeluaranObat) {
            return;
        }

        foreach ($pengeluaran->detailPengeluaranObat as $detail) {
            $namaObat = $detail->namaObat;

            if (!$namaObat) {
                continue;
            }

            // Ambil rata-rata pengeluaran 30 hari terakhir (sekarang lebih fresh)
            $rataRataPengeluaran = $this->getRataRataPengeluaran($namaObat->id);

            // Jika masih 0, gunakan jumlah keluar saat ini
            if ($rataRataPengeluaran == 0) {
                $rataRataPengeluaran = $detail->jumlah_keluar ?? 10;
            }

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

        return $rataRata ?? 0;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
