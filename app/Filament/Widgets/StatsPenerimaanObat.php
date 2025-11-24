<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsPenerimaanObat extends BaseWidget
{
    protected ?string $heading = 'Dashboard PustuMed';
    protected ?string $description = 'Ini adalah dashboard PustuMed.';
    protected static ?string $pollingInterval = '5s';
    protected static bool $isLazy = false;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;

        $prev = $now->copy()->subMonth();
        $prevYear = $prev->year;
        $prevMonth = $prev->month;

        // total penerimaan bulan ini
        $penerimaanThis = (int) DB::table('detail_penerimaan_obat')
            ->join('penerimaan_obat', 'detail_penerimaan_obat.penerimaan_obat_id', '=', 'penerimaan_obat.id')
            ->whereYear('penerimaan_obat.tanggal_penerimaan', $year)
            ->whereMonth('penerimaan_obat.tanggal_penerimaan', $month)
            ->sum(DB::raw('COALESCE(detail_penerimaan_obat.jumlah_masuk,0)'));

        // total penerimaan bulan sebelumnya
        $penerimaanPrev = (int) DB::table('detail_penerimaan_obat')
            ->join('penerimaan_obat', 'detail_penerimaan_obat.penerimaan_obat_id', '=', 'penerimaan_obat.id')
            ->whereYear('penerimaan_obat.tanggal_penerimaan', $prevYear)
            ->whereMonth('penerimaan_obat.tanggal_penerimaan', $prevMonth)
            ->sum(DB::raw('COALESCE(detail_penerimaan_obat.jumlah_masuk,0)'));

        // total pengeluaran bulan ini
        $pengeluaranThis = (int) DB::table('detail_pengeluaran_obat')
            ->join('pengeluaran_obat', 'detail_pengeluaran_obat.pengeluaran_obat_id', '=', 'pengeluaran_obat.id')
            ->whereYear('pengeluaran_obat.tanggal_pengeluaran', $year)
            ->whereMonth('pengeluaran_obat.tanggal_pengeluaran', $month)
            ->sum(DB::raw('COALESCE(detail_pengeluaran_obat.jumlah_keluar,0)'));

        // total pengeluaran bulan sebelumnya
        $pengeluaranPrev = (int) DB::table('detail_pengeluaran_obat')
            ->join('pengeluaran_obat', 'detail_pengeluaran_obat.pengeluaran_obat_id', '=', 'pengeluaran_obat.id')
            ->whereYear('pengeluaran_obat.tanggal_pengeluaran', $prevYear)
            ->whereMonth('pengeluaran_obat.tanggal_pengeluaran', $prevMonth)
            ->sum(DB::raw('COALESCE(detail_pengeluaran_obat.jumlah_keluar,0)'));

        $makeDescription = function (int $current, int $previous) {
            if ($previous === 0) {
                return $current === 0 ? ['—', null] : ['↑ ' . number_format($current), 'heroicon-m-arrow-trending-up'];
            }

            $diff = $current - $previous;
            $pct = round(($diff / max(1, $previous)) * 100, 1);
            if ($diff > 0) {
                return ["↑ {$pct}% vs bulan sebelumnya", 'heroicon-m-arrow-trending-up'];
            } elseif ($diff < 0) {
                return ["↓ {$pct}% vs bulan sebelumnya", 'heroicon-m-arrow-trending-down'];
            }

            return ['No change', null];
        };

        [$penerimaanDesc, $penerimaanIcon] = $makeDescription($penerimaanThis, $penerimaanPrev);
        [$pengeluaranDesc, $pengeluaranIcon] = $makeDescription($pengeluaranThis, $pengeluaranPrev);

        return [
            Stat::make('Penerimaan (bulan ini)', number_format($penerimaanThis))
                ->description($penerimaanDesc)
                ->descriptionIcon($penerimaanIcon ?? 'heroicon-m-minus', IconPosition::After)
                ->color('success'),

            Stat::make('Pengeluaran (bulan ini)', number_format($pengeluaranThis))
                ->description($pengeluaranDesc)
                ->descriptionIcon($pengeluaranIcon ?? 'heroicon-m-minus', IconPosition::After)
                ->color('danger'),
        ];
    }
}
