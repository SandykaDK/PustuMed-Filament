<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LaporanStok;
use Doctrine\DBAL\Schema\Column;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LaporanStokResource\Pages;
use App\Filament\Resources\LaporanStokResource\RelationManagers;

class LaporanStokResource extends Resource
{
    protected static ?string $model = LaporanStok::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Stok';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Laporan Stok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('namaObat.nama_obat')
                    ->label('Nama Obat')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('stok_awal')
                    ->label('Stok Awal')
                    ->sortable(),
                TextColumn::make('jumlah_masuk')
                    ->label('Jumlah Masuk')
                    ->sortable(),

                TextColumn::make('jumlah_keluar')
                    ->label('Jumlah Keluar')
                    ->sortable(),

                TextColumn::make('stok_akhir')
                    ->label('Stok Akhir')
                    ->sortable(),

                TextColumn::make('lokasi_penyimpanan')
                    ->label('Lokasi Penyimpanan')
                    ->searchable(),

                TextColumn::make('status_stok')
                    ->label('Status Stok')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tersedia' => 'success',
                        'Hampir Habis' => 'warning',
                        'Habis' => 'danger',
                        'Kadaluarsa' => 'grey',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanStoks::route('/'),
            'create' => Pages\CreateLaporanStok::route('/create'),
            'edit' => Pages\EditLaporanStok::route('/{record}/edit'),
        ];
    }
}
