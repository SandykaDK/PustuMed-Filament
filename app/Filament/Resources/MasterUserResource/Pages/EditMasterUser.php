<?php

namespace App\Filament\Resources\MasterUserResource\Pages;

use App\Filament\Resources\MasterUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterUser extends EditRecord
{
    protected static string $resource = MasterUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
