<?php

namespace App\Filament\Resources\PenerimaanObatResource\Pages;

use App\Filament\Resources\PenerimaanObatResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPenerimaanObat extends ViewRecord
{
    protected static string $resource = PenerimaanObatResource::class;

    public function getTitle(): string
    {
        return 'Detail Penerimaan Obat';
    }
}
