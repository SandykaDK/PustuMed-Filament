<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ChartPenerimaanObat extends ChartWidget
{
    protected static ?string $heading = 'Penerimaan Obat';
    protected static ?string $description = 'Ini adalah dashboard penerimaan obat.';
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '5s';
    protected static bool $isLazy = true;
    public ?string $filter = 'today';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
