<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierObatResource\Pages;
use App\Models\SupplierObat;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupplierObatResource extends Resource
{
    protected static ?string $model = SupplierObat::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Master Supplier';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Supplier';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_supplier')
                    ->label('Kode Supplier')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('alamat_supplier')
                    ->label('Alamat Supplier')
                    ->required(),
                TextInput::make('telepon_supplier')
                    ->tel()
                    ->minLength(10)
                    ->maxLength(13)
                    ->unique(ignoreRecord: true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_supplier')
                    ->label('Kode Supplier')
                    ->sortable(),
                TextColumn::make('nama_supplier')
                    ->label('Kode Supplier')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('alamat_supplier')
                    ->label('Kode Supplier'),
                TextColumn::make('telepon_supplier')
                    ->label('Kode Supplier'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSupplierObats::route('/'),
            'create' => Pages\CreateSupplierObat::route('/create'),
            'edit' => Pages\EditSupplierObat::route('/{record}/edit'),
        ];
    }
}
