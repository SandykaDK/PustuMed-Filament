<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use App\Models\SatuanObat;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SatuanObatResource\Pages;

class SatuanObatResource extends Resource
{
    protected static ?string $model = SatuanObat::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Satuan Obat';
    protected static ?string $pluralModelLabel = 'Satuan Obat';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Satuan Obat';
    protected static ?string $recordTitleAttribute = 'satuan_obat';

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
            'index' => Pages\ListSatuanObats::route('/'),
            'create' => Pages\CreateSatuanObat::route('/create'),
            'edit' => Pages\EditSatuanObat::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->satuan_obat;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
