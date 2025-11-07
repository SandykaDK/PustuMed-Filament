<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\JenisPengeluaran;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\JenisPengeluaranResource\Pages;

class JenisPengeluaranResource extends Resource
{
    protected static ?string $model = JenisPengeluaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Jenis Pengeluaran';
    protected static ?string $pluralModelLabel = 'Jenis Pengeluaran';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Jenis Pengeluaran';
    protected static ?string $recordTitleAttribute = 'jenis_pengeluaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_pengeluaran')
                    ->label('Kode Pengeluaran')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('jenis_pengeluaran')
                    ->label('Jenis Pengeluaran')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('kode_pengeluaran')
                ->label('Kode Pengeluaran')
                ->searchable()
                ->sortable(),
            TextColumn::make('jenis_pengeluaran')
                ->label('Jenis Pengeluaran')
                ->searchable()
                ->sortable(),
            TextColumn::make('keterangan')
                ->label('Keterangan')
                ->searchable(),
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
            'index' => Pages\ListJenisPengeluarans::route('/'),
            'create' => Pages\CreateJenisPengeluaran::route('/create'),
            'edit' => Pages\EditJenisPengeluaran::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->jenis_pengeluaran;
    }
}
