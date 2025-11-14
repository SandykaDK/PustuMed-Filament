<?php

namespace App\Filament\Resources\NamaObatResource\Widgets;

use Filament\Tables\Table;
use App\Models\PenerimaanObat;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class HistoryPenerimaanObat extends BaseWidget
{
    protected static ?string $heading = 'Riwayat Penerimaan Obat';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        $namaObatId = $this->record->id ?? null;

        return $table
            ->query(
                PenerimaanObat::query()
                    ->with('detailPenerimaanObat.satuan')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('No.')
                    ->sortable(),

                TextColumn::make('no_batch')
                    ->label('No. Batch'),

                TextColumn::make('tanggal_penerimaan')
                    ->label('Tanggal Penerimaan')
                    ->date('d-m-Y')
                    ->sortable(),

                TextColumn::make('detailPenerimaanObat.jumlah_masuk')
                    ->label('Jumlah Masuk')
                    ->formatStateUsing(function ($state, $record) {
                        $detail = $record->detailPenerimaanObat->first();
                        return $detail ? $detail->jumlah_masuk ?? $detail->jumlah ?? 0 : 0;
                    })
                    ->sortable(),

                TextColumn::make('detailPenerimaanObat.satuan_id')
                    ->label('Satuan')
                    ->formatStateUsing(function ($state, $record) {
                        $detail = $record->detailPenerimaanObat->first();
                        $satuan = $detail->satuan ?? null;

                        return $satuan->satuan_obat ?? $satuan->satuan_obat ?? ($state ?? '-');
                    }),
            ])
            ->defaultSort('tanggal_penerimaan', 'desc')
            ->striped();
    }

    public static function canView(): bool
    {
        return Auth::user()?->role == 'kepala_pustu';
    }
}
