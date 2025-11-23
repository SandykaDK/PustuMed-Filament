<?php

namespace App\Filament\Resources\PengeluaranObatResource\Pages;

use App\Filament\Resources\PengeluaranObatResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPengeluaranObat extends ViewRecord
{
    protected static string $resource = PengeluaranObatResource::class;

    public function getTitle(): string
    {
        return 'Detail Pengeluaran Obat';
    }
}
