<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Pasien;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PasienResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PasienResource extends Resource
{
    protected static ?string $model = Pasien::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Daftar Pasien';
    protected static ?string $pluralModelLabel = 'Daftar Pasien';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Pasien';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Pasien')
                    ->suffixIcon('heroicon-m-user-circle')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('no_telepon')
                    ->label('No. Telepon')
                    ->suffixIcon('heroicon-m-phone')
                    ->numeric()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(13),
                TextInput::make('no_bpjs')
                    ->label('No. BPJS')
                    ->maxLength(13)
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->label('Alamat'),
                TextColumn::make('no_telepon')
                    ->label('No. Telepon')
                    ->searchable(),
                TextColumn::make('no_bpjs')
                    ->label('No. BPJS')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPasiens::route('/'),
            'create' => Pages\CreatePasien::route('/create'),
            'edit' => Pages\EditPasien::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    // public static function getGlobalSearchResultTitle(Model $record): string
    // {
    //     return $record->nama;
    // }
}
