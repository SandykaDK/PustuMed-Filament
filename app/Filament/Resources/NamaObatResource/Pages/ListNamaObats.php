<?php

namespace App\Filament\Resources\NamaObatResource\Pages;

use Filament\Actions;
use App\Models\NamaObat;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\NamaObatResource;

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

    public function getTabs(): array{
        return [
            'all' => Tab::make('Semua')
                // ->badge(NamaObat::query()->withTrashed()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->withTrashed()),
            'active' => Tab::make('Aktif')
                // ->badge(NamaObat::query()->withoutTrashed()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()),
            'inactive' => Tab::make('Tidak Aktif')
                // ->badge(NamaObat::query()->onlyTrashed()->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }

    public function getDefaultActiveTab(): string
    {
        return 'active';
    }
}
