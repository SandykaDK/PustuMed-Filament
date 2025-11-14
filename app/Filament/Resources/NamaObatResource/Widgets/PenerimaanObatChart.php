<?php

namespace App\Filament\Resources\NamaObatResource\Widgets;

use App\Models\PenerimaanObat;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PenerimaanObatChart extends ChartWidget
{
    protected static ?string $heading = 'Penerimaan Obat Per Bulan';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = '1';
    public ?string $filter = null;

    protected function getData(): array
    {
        $selectedYear = $this->filter
            ? (int) str_replace('year_', '', $this->filter)
            : now()->year;

        $data = PenerimaanObat::join('detail_penerimaan_obat', 'penerimaan_obat.id', '=', 'detail_penerimaan_obat.penerimaan_obat_id')
            ->selectRaw('MONTH(penerimaan_obat.tanggal_penerimaan) as bulan, SUM(detail_penerimaan_obat.jumlah_masuk) as total')
            ->whereYear('penerimaan_obat.tanggal_penerimaan', $selectedYear)
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
                    'label' => 'Jumlah Penerimaan',
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
