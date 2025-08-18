<?php

namespace App\Filament\Resources\SatuanObatResource\Pages;

use App\Filament\Resources\SatuanObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSatuanObat extends EditRecord
{
    protected static string $resource = SatuanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
