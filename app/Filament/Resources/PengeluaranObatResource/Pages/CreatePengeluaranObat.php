<?php

namespace App\Filament\Resources\PengeluaranObatResource\Pages;

use App\Models\NamaObat;
use App\Models\StokObat;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PengeluaranObatResource;

class CreatePengeluaranObat extends CreateRecord
{
    protected static string $resource = PengeluaranObatResource::class;

    public function getTitle(): string
    {
        return 'Tambah Pengeluaran Obat';
    }

    protected function afterCreate(): void
    {
        $this->calculateMinMaxStock();
        $this->updateStockFromPengeluaran();
    }

    protected function updateStockFromPengeluaran(): void
    {
        $pengeluaran = $this->record;

        if (!$pengeluaran->detailPengeluaranObat) {
            return;
        }

        foreach ($pengeluaran->detailPengeluaranObat as $detail) {
            $stokObatId = $detail->detail_penerimaan_obat_id;
            $namaObatId = $detail->nama_obat_id;
            $qty = (int) ($detail->jumlah_keluar ?? 0);

            if (!$stokObatId || !$namaObatId || $qty <= 0) {
                continue;
            }

            // Kurangi stok dari stok_obat
            StokObat::find($stokObatId)?->decrement('stok', $qty);

            // Update total stok di nama_obat
            $totalStokNamaObat = StokObat::where('nama_obat_id', $namaObatId)
                ->sum('stok');

            NamaObat::find($namaObatId)?->update([
                'stok' => $totalStokNamaObat,
            ]);
        }
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

            // Pemakaian maksimum per hari (30 hari terakhir)
            $maxPerDay = $this->getPemakaianMaksimumPerHari($namaObat->id);

            $leadTime = $namaObat->lead_time ?? 7;

            // Safety Stock
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
}
