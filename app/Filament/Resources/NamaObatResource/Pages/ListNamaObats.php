<?php

namespace App\Filament\Resources\NamaObatResource\Pages;

use App\Models\NamaObat;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\NamaObatExporter;
use App\Filament\Resources\NamaObatResource;

class ListNamaObats extends ListRecords
{
    protected static string $resource = NamaObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah')
                ->icon('heroicon-o-plus')
                ->color('success'),
            // ExportAction::make()
            //     ->exporter(NamaObatExporter::class)
            //     ->icon('heroicon-o-arrow-down-tray')
            //     ->color('success')
            //     ->formats([
            //         ExportFormat::Xlsx,
            //     ])
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
