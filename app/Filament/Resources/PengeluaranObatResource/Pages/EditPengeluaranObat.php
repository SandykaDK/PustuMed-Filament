<?php

namespace App\Filament\Resources\PengeluaranObatResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PengeluaranObatResource;

class EditPengeluaranObat extends EditRecord
{
    protected static string $resource = PengeluaranObatResource::class;

    protected function afterSave(): void
    {
        $this->updateStockFromPengeluaran();
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

            $rataRataPengeluaran = $this->getRataRataPengeluaran($namaObat->id);

            if ($rataRataPengeluaran == 0) {
                $rataRataPengeluaran = $detail->jumlah_keluar ?? 10;
            }

            $maxPerDay = $this->getPemakaianMaksimumPerHari($namaObat->id);
            $leadTime = $namaObat->lead_time ?? 7;

            $safetyStockCalc = ($maxPerDay - $rataRataPengeluaran) * $leadTime;
            $safetyStock = (int) ceil(max(0, $safetyStockCalc));

            $minimumStock = (int) ceil(($rataRataPengeluaran * $leadTime) + $safetyStock);
            $maximumStock = (int) ceil(2 * ($rataRataPengeluaran * $leadTime) + $safetyStock);
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

    protected function updateStockFromPengeluaran(): void
    {
        $pengeluaran = $this->record;

        if (! $pengeluaran->detailPengeluaranObat) {
            return;
        }

        foreach ($pengeluaran->detailPengeluaranObat as $detail) {
            $namaObatId = $detail->nama_obat_id ?? ($detail->namaObat->id ?? null);
            $qty = (int) ($detail->jumlah_keluar ?? $detail->jumlah ?? 0);

            if (! $namaObatId || $qty <= 0) {
                continue;
            }

            DB::table('nama_obat')
                ->where('id', $namaObatId)
                ->update(['stok' => DB::raw("GREATEST(stok - {$qty}, 0)")]);
        }
    }

    protected function getRataRataPengeluaran($namaObatId): float
    {
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
