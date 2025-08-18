<?php

namespace App\Filament\Resources\PenerimaanObatResource\Pages;

use Filament\Actions;
use App\Models\LaporanStok;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PenerimaanObatResource;

class EditPenerimaanObat extends EditRecord
{
    protected static string $resource = PenerimaanObatResource::class;

    protected function afterSave(): void
    {
        foreach ($this->record->detailPenerimaanObat as $detail) {
            $laporanStok = LaporanStok::where('nama_obat_id', $detail->nama_obat_id)->first();

            $stokAkhir = ($laporanStok ? $laporanStok->stok_awal : 0) + $detail->jumlah_masuk - ($laporanStok ? $laporanStok->jumlah_keluar : 0);

            // Tentukan status stok
            $statusStok = 'Tersedia';
            if ($stokAkhir == 0) {
                $statusStok = 'Habis';
            } elseif ($stokAkhir < 10) {
                $statusStok = 'Hampir Habis';
            }
            if ($detail->tanggal_kadaluwarsa && strtotime($detail->tanggal_kadaluwarsa) < strtotime(now())) {
                $statusStok = 'Kadaluwarsa';
            }

            if ($laporanStok) {
                $laporanStok->jumlah_masuk = $detail->jumlah_masuk;
                $laporanStok->stok_akhir = $stokAkhir;
                $laporanStok->status_stok = $statusStok;
                $laporanStok->tanggal_kadaluwarsa_terdekat = $detail->tanggal_kadaluwarsa;
                $laporanStok->lokasi_penyimpanan = $detail->lokasi_penyimpanan ?? '';
                $laporanStok->save();
            } else {
                LaporanStok::create([
                    'nama_obat_id' => $detail->nama_obat_id,
                    'stok_awal' => 0,
                    'jumlah_masuk' => $detail->jumlah_masuk,
                    'jumlah_keluar' => 0,
                    'stok_akhir' => $stokAkhir,
                    'lokasi_penyimpanan' => $detail->lokasi_penyimpanan ?? '',
                    'tanggal_kadaluwarsa_terdekat' => $detail->tanggal_kadaluwarsa,
                    'status_stok' => $statusStok,
                ]);
            }
        }
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
