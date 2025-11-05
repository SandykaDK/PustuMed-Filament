<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SatuanObatResource\Pages;
use App\Filament\Resources\SatuanObatResource\RelationManagers;
use App\Models\SatuanObat;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SatuanObatResource extends Resource
{
    protected static ?string $model = SatuanObat::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Satuan Obat';
    protected static ?string $pluralModelLabel = 'Satuan Obat';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Satuan Obat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_satuan')
                    ->label('Kode Satuan')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('satuan_obat')
                    ->label('Satuan Obat')
                    ->unique(ignoreRecord: true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_satuan')
                    ->label('Kode Satuan')
                    ->sortable(),
                TextColumn::make('satuan_obat')
                    ->label('Satuan Obat')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListSatuanObats::route('/'),
            'create' => Pages\CreateSatuanObat::route('/create'),
            'edit' => Pages\EditSatuanObat::route('/{record}/edit'),
        ];
    }
}
