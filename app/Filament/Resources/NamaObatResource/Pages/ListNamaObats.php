<?php

namespace App\Filament\Resources\NamaObatResource\Pages;

use App\Filament\Resources\NamaObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNamaObats extends ListRecords
{
    protected static string $resource = NamaObatResource::class;

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
