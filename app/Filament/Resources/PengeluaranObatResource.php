<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\NamaObat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PengeluaranObat;
use Filament\Resources\Resource;
use App\Models\DetailPenerimaanObat;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PengeluaranObatResource\Pages;

class PengeluaranObatResource extends Resource
{
    protected static ?string $model = PengeluaranObat::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';
    protected static ?string $navigationLabel = 'Pengeluaran Obat';
    protected static ?string $pluralModelLabel = 'Pengeluaran Obat';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Pengeluaran Obat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tanggal Pengeluaran
                DatePicker::make('tanggal_pengeluaran')
                    ->label('Tanggal Pengeluaran')
                    ->required(),

                // User
                Select::make('user_id')
                    ->label('User')
                    ->relationship('User', 'name')
                    ->suffixIcon('heroicon-m-user-circle')
                    ->required(),

                // Tujuan Pengeluaran
                Select::make('tujuan_pengeluaran')
                    ->label('Tujuan Pengeluaran')
                    ->relationship('jenisPengeluaran', 'jenis_pengeluaran')
                    ->required(),

                // Keterangan
                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->nullable(),

                Repeater::make('detailPengeluaranObat')
                    ->label('Detail Pengeluaran Obat')
                    ->relationship('detailPengeluaranObat')
                    ->schema([
                        // Nama Obat
                        Select::make('nama_obat_id')
                            ->label('Nama Obat')
                            ->reactive()
                            ->relationship('namaObat', 'nama_obat')
                            ->afterStateUpdated(function($state, callable $set){
                                $namaObat = NamaObat::find($state);
                                if($namaObat){
                                    $set('satuan_id', $namaObat->satuan_obat_id);
                                    $set('lokasi_penyimpanan', $namaObat->lokasi_penyimpanan);
                                }
                                $set('tanggal_kadaluwarsa', null);
                                $set('no_batch', null);
                            })
                            ->required(),

                        Select::make('detail_penerimaan_obat_id')
                            ->label('Tanggal Kadaluwarsa')
                            ->options(function ($get) {
                                $namaObatId = $get('nama_obat_id');
                                if (!$namaObatId) return [];
                                return DetailPenerimaanObat::where('nama_obat_id', $namaObatId)
                                    ->where('jumlah_masuk', '>', 0)
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        $label = date('d-m-Y', strtotime($item->tanggal_kadaluwarsa));
                                        return [$item->id => $label];
                                    })->toArray();
                            })
                            ->reactive()
                            ->required(),

                        // Satuan Obat
                        Select::make('satuan_id')
                            ->label('Satuan Obat')
                            ->reactive()
                            ->relationship('satuan', 'satuan_obat')
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        // Jumlah Keluar
                        TextInput::make('jumlah_keluar')
                            ->label('Jumlah Keluar')
                            ->numeric()
                            ->required(),

                        // Lokasi Penyimpanan
                        TextInput::make('lokasi_penyimpanan')
                            ->label('Lokasi Penyimpanan')
                            ->reactive()
                            ->disabled()
                            ->dehydrated()
                            ->nullable(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // No. Batch
                TextColumn::make('no_batch')
                    ->label('No. Batch')
                    ->sortable(),

                // Tanggal Pengeluaran
                TextColumn::make('tanggal_pengeluaran')
                    ->label('Tanggal Pengeluaran')
                    ->sortable(),

                // Total Jumlah Obat Keluar
                TextColumn::make('total_jumlah_keluar')
                    ->label('Total Jumlah Keluar Obat')
                    ->getStateUsing(function ($record) {
                        return $record->detailPengeluaranObat->sum('jumlah_keluar');
                    }),

                // Tujuan Pengeluaran
                TextColumn::make('tujuan_pengeluaran')
                    ->label('Tujuan Pengeluaran')
                    ->sortable(),

                // Keterangan
                TextColumn::make('keterangan')
                    ->label('Keterangan')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPengeluaranObats::route('/'),
            'create' => Pages\CreatePengeluaranObat::route('/create'),
            'edit' => Pages\EditPengeluaranObat::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
