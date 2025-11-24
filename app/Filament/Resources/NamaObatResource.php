<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\NamaObat;
use Filament\Forms\Form;
use App\Models\JenisObat;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\NamaObatResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NamaObatResource extends Resource
{
    protected static ?string $model = NamaObat::class;
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $navigationLabel = 'Daftar Obat';
    protected static ?string $pluralModelLabel = 'Daftar Obat';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Nama Obat';
    protected static ?string $recordTitleAttribute = 'nama_obat';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_obat')
                    ->label('Kode Obat')
                    ->placeholder('Masukkan Kode Obat')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('nama_obat')
                    ->label('Nama Obat')
                    ->placeholder('Masukkan Nama Obat')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('jenis_obat_id')
                    ->label('Jenis Obat')
                    ->options(function ($get) {
                        $selectedId = $get('jenis_obat_id');

                        // Hanya ambil yang aktif
                        $active = JenisObat::whereNull('deleted_at')->get();

                        // Jika sedang mengedit dan saat ini terpilih adalah trashed, sertakan juga itu agar tetap terlihat
                        if ($selectedId) {
                            $current = JenisObat::withTrashed()->find($selectedId);
                            if ($current && $current->trashed()) {
                                // hindari duplikat
                                if (! $active->contains('id', $current->id)) {
                                    $active->push($current);
                                }
                            }
                        }

                        return $active->sortBy('jenis_obat')->pluck('jenis_obat', 'id')->toArray();
                    })
                    ->required()
                    ->rules([
                        function ($get) {
                            return function ($attribute, $value, $fail) use ($get) {
                                $selected = JenisObat::withTrashed()->find($value);
                                if (! $selected) {
                                    $fail('Jenis Obat tidak valid.');
                                    return;
                                }

                                // Jika jenis obat terhapus (trashed)
                                if ($selected->trashed()) {
                                    // Cek apakah kita sedang mengedit record yang memang sudah memakai jenis obat ini
                                    $recordId = request()->route('record'); // null saat create
                                    if (! $recordId) {
                                        $fail('Jenis Obat ini sudah tidak aktif dan tidak dapat dipilih.');
                                        return;
                                    }

                                    $original = NamaObat::find($recordId);
                                    if (! $original || $original->jenis_obat_id !== $selected->id) {
                                        $fail('Jenis Obat ini sudah tidak aktif dan tidak dapat dipilih.');
                                        return;
                                    }
                                }
                            };
                        },
                    ])
                    ->helperText(function ($get) {
                        $selectedId = $get('jenis_obat_id');
                        if (! $selectedId) {
                            return 'Pilih jenis obat (hanya yang aktif akan tampil).';
                        }

                        $selected = JenisObat::withTrashed()->find($selectedId);
                        if (! $selected) return null;

                        if ($selected->trashed()) {
                            return new \Illuminate\Support\HtmlString('
                                <span class="flex items-center gap-2 text-amber-600">
                                    <span>Jenis Obat ini sudah tidak aktif</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l5.451 9.69c.75 1.334-.213 2.94-1.742 2.94H4.548c-1.529 0-2.492-1.606-1.742-2.94l5.451-9.69zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V6a1 1 0 012 0v4a1 1 0 01-1 1z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            ');
                        }

                        return null;
                    }),
                Select::make('satuan_obat_id')
                    ->label('Satuan Obat')
                    ->relationship('satuanObat', 'satuan_obat')
                    ->required(),
                TextInput::make('lokasi_penyimpanan')
                    ->label('Lokasi Penyimpanan')
                    ->placeholder('Masukkan Lokasi Penyimpanan')
                    ->nullable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_obat')
                    ->label('Kode Obat')
                    ->sortable(),
                TextColumn::make('nama_obat')
                    ->label('Nama Obat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenisObat.jenis_obat')
                    ->label('Jenis Obat')
                    ->sortable(),
                TextColumn::make('satuanObat.satuan_obat')
                    ->label('Satuan Obat')
                    ->sortable(),
                TextColumn::make('lokasi_penyimpanan')
                    ->label('Lokasi Penyimpanan')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable(),
                TextColumn::make('minMax.minimum_stock')
                    ->label('Stok Minimum')
                    ->sortable()
                    ->toggleable(),
                    // ->toggledHiddenByDefault(),
                TextColumn::make('minMax.maximum_stock')
                    ->label('Stok Maksimum')
                    ->sortable()
                    ->toggleable(),
                    // ->toggledHiddenByDefault(),
                TextColumn::make('minMax.safety_stock')
                    ->label('Safety Stock')
                    ->sortable()
                    ->toggleable(),
                    // ->toggledHiddenByDefault(),
                TextColumn::make('minMax.reorder_point')
                    ->label('Reorder Point')
                    ->sortable()
                    ->toggleable(),
                    // ->toggledHiddenByDefault(),
                TextColumn::make('minMax.lead_time')
                    ->label('Lead Time (hari)')
                    ->sortable()
                    ->toggleable(),
                    // ->toggledHiddenByDefault(),
            ])
            // ->headerActions([
            //     ExportAction::make()
            //         ->exporter(NamaObatExporter::class),
            // ])
            ->filters([
                SelectFilter::make('jenis_obat_id')
                    ->relationship('jenisObat', 'jenis_obat')
                    ->label('Jenis Obat')
                    ->preload(),
                SelectFilter::make('satuan_obat_id')
                    ->relationship('satuanObat', 'satuan_obat')
                    ->label('Satuan Obat')
                    ->preload(),
                // TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNamaObats::route('/'),
            'create' => Pages\CreateNamaObat::route('/create'),
            'edit' => Pages\EditNamaObat::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->nama_obat;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Jenis Obat' => $record->jenisObat->jenis_obat,
            'Satuan Obat' => $record->satuanObat->satuan_obat,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['jenisObat', 'satuanObat']);
    }
}
