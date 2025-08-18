<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisObatResource\Pages;
use App\Filament\Resources\JenisObatResource\RelationManagers;
use App\Models\JenisObat;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisObatResource extends Resource
{
    protected static ?string $model = JenisObat::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Jenis Obat';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Jenis Obat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_jenis')
                    ->label('Kode Jenis')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('jenis_obat')
                    ->label('Jenis Obat')
                    ->required()
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_jenis'),
                TextColumn::make('jenis_obat')
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
            'index' => Pages\ListJenisObats::route('/'),
            'create' => Pages\CreateJenisObat::route('/create'),
            'edit' => Pages\EditJenisObat::route('/{record}/edit'),
        ];
    }
}
