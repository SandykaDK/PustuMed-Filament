<?php

namespace App\Filament\Resources\PasienResource\Pages;

use App\Filament\Resources\PasienResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPasiens extends ListRecords
{
    protected static string $resource = PasienResource::class;

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
