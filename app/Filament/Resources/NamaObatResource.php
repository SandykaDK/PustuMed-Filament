<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\NamaObat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\NamaObatResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NamaObatResource extends Resource
{
    protected static ?string $model = NamaObat::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Nama Obat';
    protected static ?string $pluralModelLabel = 'Nama Obat';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 2;
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
                    ->relationship('jenisObat', 'jenis_obat')
                    ->required(),
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
                    ->sortable(),
            ])
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
