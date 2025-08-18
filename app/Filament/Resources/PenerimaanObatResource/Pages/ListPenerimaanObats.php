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

    public function getTabs(): array
    {
        $years = PenerimaanObat::selectRaw('YEAR(tanggal_penerimaan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $tabs = [];

        $tabs['all'] = Tab::make('Semua')
            ->modifyQueryUsing(fn (Builder $query) => $query);

        foreach ($years as $year) {
            $tabs[$year] = Tab::make("$year")
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereYear('tanggal_penerimaan', $year)
                );
        }

        return $tabs;
    }

}
