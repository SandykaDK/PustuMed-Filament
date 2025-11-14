<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\NamaObat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PenerimaanObat;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PenerimaanObatResource\Pages;
use Filament\Forms\Components\Repeater;

class PenerimaanObatResource extends Resource
{
    protected static ?string $model = PenerimaanObat::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';
    protected static ?string $navigationLabel = 'Penerimaan Obat';
    protected static ?string $pluralModelLabel = 'Penerimaan Obat';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Penerimaan Obat';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tanggal Penerimaan
                DatePicker::make('tanggal_penerimaan')
                    ->label('Tanggal Penerimaan')
                    // ->minDate(now())
                    ->required(), // Tambahkan Jam jika perlu (masih belum fix)

                // User
                Select::make('user_id')
                    ->label('User')
                    ->relationship('User', titleAttribute: 'name')
                    ->suffixIcon('heroicon-m-user-circle')
                    ->default(Auth::user()->id),
                    // ->disabled(),

                Repeater::make('detailPenerimaanObat')
                    ->label('Detail Penerimaan Obat')
                    ->relationship('detailPenerimaanObat')
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
                            })
                            ->required(),

                        // Satuan Obat
                        Select::make('satuan_id')
                            ->label('Satuan Obat')
                            ->reactive()
                            ->relationship('satuan', 'satuan_obat')
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        // Tanggal Kadaluarsa
                        DatePicker::make('tanggal_kadaluwarsa')
                            ->label('Tanggal Kadaluarsa')
                            // ->minDate(now())
                            ->required(),

                        // Jumlah Masuk
                        TextInput::make('jumlah_masuk')
                            ->label('Jumlah Masuk')
                            ->numeric()
                            ->required(),

                        // Lokasi Penyimpanan
                        TextInput::make('lokasi_penyimpanan')
                            ->label('Lokasi Penyimpanan')
                            ->reactive()
                            ->disabled()
                            ->dehydrated()
                            ->nullable(),

                        // No. Batch
                        TextInput::make('no_batch')
                            ->label('No. Batch')
                            ->required()
                            ->hidden(),
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

                // Tanggal Penerimaan
                TextColumn::make('tanggal_penerimaan')
                    ->label('Tanggal Masuk')
                    ->sortable(),
                    // ->toggleable(isToggledHiddenByDefault: true),

                // Total Jumlah Obat Masuk
                TextColumn::make('total_jumlah_masuk')
                    ->label('Total Jumlah Masuk Obat')
                    ->getStateUsing(function ($record) {
                        return $record->detailPenerimaanObat->sum('jumlah_masuk');
                    }),
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
            'index' => Pages\ListPenerimaanObats::route('/'),
            'create' => Pages\CreatePenerimaanObat::route('/create'),
            'edit' => Pages\EditPenerimaanObat::route('/{record}/edit'),
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
