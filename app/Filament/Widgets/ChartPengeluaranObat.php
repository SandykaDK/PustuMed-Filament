<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ChartPengeluaranObat extends ChartWidget
{
    protected static ?string $heading = 'Pengeluaran Obat';
    protected static ?string $description = 'Ini adalah dashboard pengeluaran obat.';
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '5s';
    protected static bool $isLazy = true;
    public ?string $filter = 'today';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $filter = $this->filter ?? 'today';

        $labels = [];
        $values = [];

        if ($filter === 'today') {
            // hourly for today
            for ($h = 0; $h < 24; $h++) {
                $labels[] = sprintf('%02d:00', $h);
                $values[$h] = 0;
            }

            $rows = DB::table('detail_pengeluaran_obat')
                ->join('pengeluaran_obat', 'detail_pengeluaran_obat.pengeluaran_obat_id', '=', 'pengeluaran_obat.id')
                ->whereDate('pengeluaran_obat.tanggal_pengeluaran', Carbon::today())
                ->selectRaw('HOUR(pengeluaran_obat.tanggal_pengeluaran) as hour, SUM(COALESCE(detail_pengeluaran_obat.jumlah_keluar,0)) as total')
                ->groupBy('hour')
                ->get();

            foreach ($rows as $r) {
                $values[(int)$r->hour] = (int)$r->total;
            }
            $data = array_values($values);
        } elseif ($filter === 'week') {
            // last 7 days
            $start = Carbon::today()->subDays(6);
            $period = [];
            for ($i = 0; $i < 7; $i++) {
                $d = $start->copy()->addDays($i);
                $key = $d->toDateString();
                $labels[] = $d->format('D d');
                $period[$key] = 0;
            }

            $rows = DB::table('detail_pengeluaran_obat')
                ->join('penerimaan_obat', 'detail_pengeluaran_obat.pengeluaran_obat_id', '=', 'penerimaan_obat.id')
                ->whereDate('penerimaan_obat.tanggal_penerimaan', '>=', $start->toDateString())
                ->selectRaw('DATE(penerimaan_obat.tanggal_penerimaan) as day, SUM(COALESCE(detail_pengeluaran_obat.jumlah_masuk,0)) as total')
                ->groupBy('day')
                ->get();

            foreach ($rows as $r) {
                $period[$r->day] = (int)$r->total;
            }
            $data = array_values($period);
        } elseif ($filter === 'month') {
            // last 30 days
            $start = Carbon::today()->subDays(29);
            $period = [];
            for ($i = 0; $i < 30; $i++) {
                $d = $start->copy()->addDays($i);
                $key = $d->toDateString();
                $labels[] = $d->format('d M');
                $period[$key] = 0;
            }

            $rows = DB::table('detail_pengeluaran_obat')
                ->join('pengeluaran_obat', 'detail_pengeluaran_obat.pengeluaran_obat_id', '=', 'pengeluaran_obat.id')
                ->whereDate('pengeluaran_obat.tanggal_pengeluaran', '>=', $start->toDateString())
                ->selectRaw('DATE(pengeluaran_obat.tanggal_pengeluaran) as day, SUM(COALESCE(detail_pengeluaran_obat.jumlah_keluar,0)) as total')
                ->groupBy('day')
                ->get();

            foreach ($rows as $r) {
                $period[$r->day] = (int)$r->total;
            }
            $data = array_values($period);
        } elseif ($filter === 'year') {
            // year - per month
            $year = Carbon::now()->year;
            $months = [];
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = Carbon::create($year, $m, 1)->format('M');
                $months[$m] = 0;
            }

            $rows = DB::table('detail_pengeluaran_obat')
                ->join('pengeluaran_obat', 'detail_pengeluaran_obat.pengeluaran_obat_id', '=', 'pengeluaran_obat.id')
                ->whereYear('pengeluaran_obat.tanggal_pengeluaran', $year)
                ->selectRaw('MONTH(pengeluaran_obat.tanggal_pengeluaran) as month, SUM(COALESCE(detail_pengeluaran_obat.jumlah_keluar,0)) as total')
                ->groupBy('month')
                ->get();

            foreach ($rows as $r) {
                $months[(int)$r->month] = (int)$r->total;
            }
            $data = array_values($months);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Pengeluaran Obat (pcs)',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
}
