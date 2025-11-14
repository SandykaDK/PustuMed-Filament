<?php

namespace App\Filament\Resources\NamaObatResource\Widgets;

use App\Models\PengeluaranObat;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PengeluaranObatChart extends ChartWidget
{
    protected static ?string $heading = 'Pengeluaran Obat Per Bulan';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = '1';
    public ?string $filter = null;

    protected function getData(): array
    {
        $selectedYear = $this->filter
            ? (int) str_replace('year_', '', $this->filter)
            : now()->year;

        $data = PengeluaranObat::join('detail_pengeluaran_obat', 'pengeluaran_obat.id', '=', 'detail_pengeluaran_obat.pengeluaran_obat_id')
            ->selectRaw('MONTH(pengeluaran_obat.tanggal_pengeluaran) as bulan, SUM(detail_pengeluaran_obat.jumlah_keluar) as total')
            ->whereYear('pengeluaran_obat.tanggal_pengeluaran', $selectedYear)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $totals = array_fill(0, 12, 0);

        foreach ($data as $item) {
            $totals[$item->bulan - 1] = $item->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengeluaran',
                    'data' => $totals,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        $currentYear = now()->year;
        $filters = [];

        for ($year = $currentYear; $year >= 2025; $year--) {
            $filters['year_' . $year] = $year;
        }

        return $filters;
    }

    public static function canView(): bool
    {
        return Auth::user()?->role == 'kepala_pustu';
    }
}
