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

    public function getTabs(): array
    {
        $years = PengeluaranObat::selectRaw('YEAR(tanggal_pengeluaran) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $tabs = [];

        $tabs['all'] = Tab::make('Semua')
            ->modifyQueryUsing(fn (Builder $query) => $query);

        foreach ($years as $year) {
            $tabs[$year] = Tab::make("$year")
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereYear('tanggal_pengeluaran', $year)
                );
        }

        return $tabs;
    }
}
