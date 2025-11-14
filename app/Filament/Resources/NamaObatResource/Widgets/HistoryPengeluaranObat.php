<?php

namespace App\Filament\Resources\NamaObatResource\Widgets;

use Filament\Tables\Table;
use App\Models\PengeluaranObat;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class HistoryPengeluaranObat extends BaseWidget
{
    protected static ?string $heading = 'Riwayat Pengeluaran Obat';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        $namaObatId = $this->record->id ?? null;

        return $table
            ->query(
                PengeluaranObat::query()
                    ->with('detailPengeluaranObat.satuan')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('No.'),

                TextColumn::make('no_batch')
                    ->label('No. Batch'),

                TextColumn::make('tanggal_pengeluaran')
                    ->label('Tanggal Pengeluaran')
                    ->date('d-m-Y')
                    ->sortable(),

                TextColumn::make('detailPengeluaranObat.jumlah_keluar')
                    ->label('Jumlah Keluar')
                    ->formatStateUsing(function ($state, $record) {
                        $detail = $record->detailPengeluaranObat->first();
                        return $detail ? $detail->jumlah_keluar ?? $detail->jumlah ?? 0 : 0;
                    })
                    ->sortable(),

                TextColumn::make('detailPengeluaranObat.satuan_id')
                    ->label('Satuan')
                    ->formatStateUsing(function ($state, $record) {
                        $detail = $record->detailPengeluaranObat->first();
                        $satuan = $detail->satuan ?? null;

                        return $satuan->satuan_obat ?? $satuan->satuan_obat ?? ($state ?? '-');
                    }),
            ])
            ->defaultSort('tanggal_pengeluaran', 'desc')
            ->striped();
    }
    public static function canView(): bool
    {
        return Auth::user()?->role == 'kepala_pustu';
    }
}
