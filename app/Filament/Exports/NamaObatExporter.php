<?php

namespace App\Filament\Exports;

use App\Models\NamaObat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class NamaObatExporter extends Exporter
{
    protected static ?string $model = NamaObat::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_obat')
                ->label('Kode Obat'),
            ExportColumn::make('nama_obat')
                ->label('Nama Obat'),
            ExportColumn::make('jenisObat.jenis_obat')
                ->label('Jenis Obat'),
            ExportColumn::make('satuanObat.satuan_obat')
                ->label('Satuan Obat'),
            ExportColumn::make('lokasi_penyimpanan')
                ->label('Lokasi Penyimpanan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your nama obat export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
