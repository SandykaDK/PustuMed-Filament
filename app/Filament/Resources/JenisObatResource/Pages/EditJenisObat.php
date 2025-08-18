<?php

namespace App\Filament\Resources\JenisObatResource\Pages;

use App\Filament\Resources\JenisObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisObat extends EditRecord
{
    protected static string $resource = JenisObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
