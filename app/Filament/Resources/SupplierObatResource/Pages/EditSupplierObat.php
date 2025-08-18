<?php

namespace App\Filament\Resources\SupplierObatResource\Pages;

use App\Filament\Resources\SupplierObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplierObat extends EditRecord
{
    protected static string $resource = SupplierObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
