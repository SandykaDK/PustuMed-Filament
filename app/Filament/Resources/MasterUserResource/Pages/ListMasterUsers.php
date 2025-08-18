<?php

namespace App\Filament\Resources\MasterUserResource\Pages;

use App\Filament\Resources\MasterUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterUsers extends ListRecords
{
    protected static string $resource = MasterUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
