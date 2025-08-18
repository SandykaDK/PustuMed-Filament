<?php

namespace App\Filament\Resources\SupplierObatResource\Pages;

use App\Filament\Resources\SupplierObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplierObats extends ListRecords
{
    protected static string $resource = SupplierObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
