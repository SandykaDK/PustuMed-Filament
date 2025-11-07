<?php

namespace App\Filament\Resources\MasterUserResource\Pages;

use App\Filament\Resources\MasterUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterUser extends CreateRecord
{
    protected static string $resource = MasterUserResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data User berhasil disimpan';
    }

        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
