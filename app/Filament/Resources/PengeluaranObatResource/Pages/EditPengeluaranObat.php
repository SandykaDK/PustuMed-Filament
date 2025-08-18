<?php

namespace App\Filament\Resources\PengeluaranObatResource\Pages;

use Filament\Actions;
use App\Models\LaporanStok;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PengeluaranObatResource;

class EditPengeluaranObat extends EditRecord
{
    protected static string $resource = PengeluaranObatResource::class;

    protected function afterSave(): void
    {
        foreach ($this->record->detailPengeluaranObat as $detail) {
            $laporanStok = LaporanStok::where('nama_obat_id', $detail->nama_obat_id)->first();

            $stokAkhir = ($laporanStok ? $laporanStok->stok_akhir : 0) - $detail->jumlah_keluar;

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
                $laporanStok->jumlah_keluar += $detail->jumlah_keluar;
                $laporanStok->stok_akhir = $stokAkhir;
                $laporanStok->status_stok = $statusStok;
                $laporanStok->tanggal_kadaluwarsa_terdekat = $detail->tanggal_kadaluwarsa;
                $laporanStok->lokasi_penyimpanan = $detail->lokasi_penyimpanan ?? '';
                $laporanStok->save();
            } else {
                LaporanStok::create([
                    'nama_obat_id' => $detail->nama_obat_id,
                    'stok_awal' => 0,
                    'jumlah_masuk' => 0,
                    'jumlah_keluar' => $detail->jumlah_keluar,
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
        ];
    }
}
