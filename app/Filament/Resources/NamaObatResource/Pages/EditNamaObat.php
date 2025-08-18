<?php

namespace App\Filament\Resources\NamaObatResource\Pages;

use App\Filament\Resources\NamaObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNamaObat extends EditRecord
{
    protected static string $resource = NamaObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
