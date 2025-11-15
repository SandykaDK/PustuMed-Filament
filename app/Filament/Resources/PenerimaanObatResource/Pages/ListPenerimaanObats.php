<?php

namespace App\Filament\Resources\PenerimaanObatResource\Pages;

use Filament\Actions;
use App\Models\PenerimaanObat;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PenerimaanObatResource;

class ListPenerimaanObats extends ListRecords
{
    protected static string $resource = PenerimaanObatResource::class;

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
