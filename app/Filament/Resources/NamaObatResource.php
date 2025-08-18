<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\NamaObat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use PhpParser\Node\Stmt\Label;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Column;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\NamaObatResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NamaObatResource\RelationManagers;

class NamaObatResource extends Resource
{
    protected static ?string $model = NamaObat::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Nama Obat';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Nama Obat';
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
                    ->label('Kode Obat'),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
            'index' => Pages\ListNamaObats::route('/'),
            'create' => Pages\CreateNamaObat::route('/create'),
            'edit' => Pages\EditNamaObat::route('/{record}/edit'),
        ];
    }
}
