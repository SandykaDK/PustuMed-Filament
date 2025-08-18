<?php

namespace App\Filament\Resources\MasterUserResource\Pages;

use App\Filament\Resources\MasterUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterUser extends CreateRecord
{
    protected static string $resource = MasterUserResource::class;
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data User berhasil disimpan';
    }
}
