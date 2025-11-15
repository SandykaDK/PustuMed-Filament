<?php

namespace App\Filament\Resources\PengeluaranObatResource\Pages;

use Filament\Actions;
use App\Models\PengeluaranObat;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PengeluaranObatResource;

class ListPengeluaranObats extends ListRecords
{
    protected static string $resource = PengeluaranObatResource::class;

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
