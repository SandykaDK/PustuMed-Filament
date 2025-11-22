<?php

namespace App\Filament\Resources\PenerimaanObatResource\Pages;

use App\Models\StokObat;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PenerimaanObatResource;

class CreatePenerimaanObat extends CreateRecord
{
    protected static string $resource = PenerimaanObatResource::class;

    public function getTitle(): string
    {
        return 'Tambah Penerimaan Obat';
    }

    protected function afterCreate(): void
    {
        $this->calculateMinMaxStock();
        $this->updateStockFromPenerimaan();
        $this->saveRecord();
    }

    protected function saveRecord(): void
    {
        $penerimaan = $this->record;

        foreach ($penerimaan->detailPenerimaanObat as $detail) {
            // Update atau buat stok_obat berdasarkan nama_obat_id dan tanggal_kadaluwarsa
            StokObat::updateOrCreate(
                [
                    'nama_obat_id' => $detail->nama_obat_id,
                    'tanggal_kadaluwarsa' => $detail->tanggal_kadaluwarsa,
                    'no_batch' => $detail->no_batch,
                ],
                [
                    'stok' => StokObat::where('nama_obat_id', $detail->nama_obat_id)
                        ->where('tanggal_kadaluwarsa', $detail->tanggal_kadaluwarsa)
                        ->where('no_batch', $detail->no_batch)
                        ->value('stok') + $detail->jumlah_masuk ?? 0,
                ]
            );
        }
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

            // Ambil rata-rata pengeluaran 30 hari terakhir (sekarang lebih fresh)
            $rataRataPengeluaran = $this->getRataRataPengeluaran($namaObat->id);

            // Jika masih 0, gunakan jumlah saat ini (coba beberapa atribut umum, default 10)
            if ($rataRataPengeluaran == 0) {
                $rataRataPengeluaran = $detail->jumlah ?? $detail->jumlah_terima ?? $detail->jumlah_masuk ?? 10;
            }

            // Pemakaian maksimum per hari (30 hari terakhir)
            $maxPerDay = $this->getPemakaianMaksimumPerHari($namaObat->id);

            $leadTime = $namaObat->lead_time ?? 7;

            // Safety Stock = (Pengeluaran maksimum perhari - pemakaian rata rata) * lead time
            $safetyStockCalc = ($maxPerDay - $rataRataPengeluaran) * $leadTime;
            $safetyStock = (int) ceil(max(0, $safetyStockCalc));

            // Minimum Stock
            $minimumStock = (int) ceil(($rataRataPengeluaran * $leadTime) + $safetyStock);

            // Maximum Stock
            $maximumStock = (int) ceil(2 * ($rataRataPengeluaran * $leadTime) + $safetyStock);

            // Reorder Point (ROP)
            $reorderPoint = (int) ceil($maximumStock - $minimumStock);

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

    protected function updateStockFromPenerimaan(): void
    {
        $penerimaan = $this->record;

        if (! $penerimaan->detailPenerimaanObat) {
            return;
        }

        foreach ($penerimaan->detailPenerimaanObat as $detail) {
            $namaObatId = $detail->nama_obat_id ?? ($detail->namaObat->id ?? null);
            $qty = (int) ($detail->jumlah ?? $detail->jumlah_terima ?? $detail->jumlah_masuk ?? 0);

            if (! $namaObatId || $qty <= 0) {
                continue;
            }

            // Increment stok secara atomic
            DB::table('nama_obat')->where('id', $namaObatId)->increment('stok', $qty);
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

    protected function getPemakaianMaksimumPerHari($namaObatId): float
    {
        $maxDaily = DB::table('detail_pengeluaran_obat')
            ->join('pengeluaran_obat', 'detail_pengeluaran_obat.pengeluaran_obat_id', '=', 'pengeluaran_obat.id')
            ->where('detail_pengeluaran_obat.nama_obat_id', $namaObatId)
            ->where('pengeluaran_obat.tanggal_pengeluaran', '>=', now()->subDays(30))
            ->selectRaw('SUM(detail_pengeluaran_obat.jumlah_keluar) as daily_total, DATE(pengeluaran_obat.tanggal_pengeluaran) as day')
            ->groupBy(DB::raw('DATE(pengeluaran_obat.tanggal_pengeluaran)'))
            ->pluck('daily_total')
            ->max();

        return (float) ($maxDaily ?? 0);
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
