<?php

namespace App\Filament\Resources\PenerimaanObatResource\Pages;

use App\Filament\Resources\PenerimaanObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenerimaanObats extends ListRecords
{
    protected static string $resource = PenerimaanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
