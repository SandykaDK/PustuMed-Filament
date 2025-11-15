<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\NamaObat;
use App\Models\StokObat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PengeluaranObat;
use App\Models\JenisPengeluaran;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use App\Models\DetailPenerimaanObat;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PengeluaranObatResource\Pages;

class PengeluaranObatResource extends Resource
{
    protected static ?string $model = PengeluaranObat::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';
    protected static ?string $navigationLabel = 'Pengeluaran Obat';
    protected static ?string $pluralModelLabel = 'Pengeluaran Obat';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 3;
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
                    ->default(Auth::user()->id)
                    // ->disabled()
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

                                return StokObat::where('nama_obat_id', $namaObatId)
                                    ->where('stok', '>', 0)
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        $label = date('d-m-Y', strtotime($item->tanggal_kadaluwarsa)) . " (Stok: {$item->stok})";
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
                            ->minValue(1)
                            ->reactive()
                            ->helperText(function ($get) {
                                $stokObatId = $get('detail_penerimaan_obat_id');
                                if (!$stokObatId) return 'Pilih tanggal kadaluwarsa terlebih dahulu';

                                $stokObat = StokObat::find($stokObatId);
                                if (!$stokObat) return '';

                                $stokTersedia = $stokObat->stok ?? 0;
                                $tglKadaluwarsa = date('d-m-Y', strtotime($stokObat->tanggal_kadaluwarsa));

                                return "Stok tersedia: {$stokTersedia} (Kadaluwarsa: {$tglKadaluwarsa})";
                            })
                            ->rules([
                                function ($get) {
                                    return function ($attribute, $value, $fail) use ($get) {
                                        $stokObatId = $get('detail_penerimaan_obat_id');
                                        $jumlahKeluar = (int) $value;

                                        if (!$stokObatId || !$value) {
                                            return;
                                        }

                                        $stokObat = StokObat::find($stokObatId);
                                        if (!$stokObat) {
                                            $fail('Tanggal kadaluwarsa tidak valid.');
                                            return;
                                        }

                                        $stokTersedia = $stokObat->stok ?? 0;
                                        $namaObat = $stokObat->namaObat->nama_obat ?? 'Obat';
                                        $tglKadaluwarsa = date('d-m-Y', strtotime($stokObat->tanggal_kadaluwarsa));

                                        if ($jumlahKeluar > $stokTersedia) {
                                            $fail("Obat '{$namaObat}' dengan tanggal kadaluwarsa {$tglKadaluwarsa} hanya memiliki stok {$stokTersedia}. Tidak bisa mengeluarkan {$jumlahKeluar}.");
                                        }
                                    };
                                },
                            ])
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
                // Tanggal Pengeluaran
                TextColumn::make('tanggal_pengeluaran')
                    ->label('Tanggal Pengeluaran')
                    ->sortable(),

                // No. Batch
                TextColumn::make('no_batch')
                    ->label('No. Batch')
                    ->sortable(),
                // Nama Obat
                TextColumn::make('detailPengeluaranObat.namaObat.nama_obat')
                    ->label('Nama Obat')
                    ->getStateUsing(function ($record) {
                        return $record->detailPengeluaranObat
                            ->pluck('namaObat.nama_obat')
                            ->join(', ');
                    }),

                // Total Jumlah Obat Keluar
                TextColumn::make('total_jumlah_keluar')
                    ->label('Total Jumlah Keluar Obat')
                    ->getStateUsing(function ($record) {
                        return $record->detailPengeluaranObat->sum('jumlah_keluar');
                    }),

                // Tujuan Pengeluaran
                TextColumn::make('tujuan_pengeluaran')
                    ->label('Tujuan Pengeluaran')
                    ->formatStateUsing(function ($state) {
                        return $state ? JenisPengeluaran::find($state)->jenis_pengeluaran : '-';
                    })
                    ->sortable(),

                // Keterangan
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('tanggal_penerimaan')
                    ->form([
                        DatePicker::make('tanggal_dari')
                            ->label('Tanggal Dari'),
                        DatePicker::make('tanggal_sampai')
                            ->label('Tanggal Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_pengeluaran', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_pengeluaran', '<=', $date),
                            );
                    }),
                // Tables\Filters\TrashedFilter::make(),
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
