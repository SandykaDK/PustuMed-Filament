<?php

namespace App\Filament\Resources\PengeluaranObatResource\Pages;

use App\Filament\Resources\PengeluaranObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengeluaranObats extends ListRecords
{
    protected static string $resource = PengeluaranObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
