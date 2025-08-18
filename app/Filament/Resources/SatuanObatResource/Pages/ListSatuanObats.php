<?php

namespace App\Filament\Resources\SatuanObatResource\Pages;

use App\Filament\Resources\SatuanObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSatuanObats extends ListRecords
{
    protected static string $resource = SatuanObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->icon('heroicon-o-plus')
                ->color('success'),
        ];
    }
}
