<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterUserResource\Pages;
use App\Filament\Resources\MasterUserResource\RelationManagers;
use App\Models\MasterUser;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterUserResource extends Resource
{
    protected static ?string $model = MasterUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Master User';
    protected static ?string $pluralModelLabel = 'Master User';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationBadgeTooltip = 'Jumlah User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_user')
                    ->label('Kode User')
                    ->placeholder('Masukkan Kode User')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('nama_user')
                    ->label('Nama User')
                    ->placeholder('Masukkan Nama User')
                    ->autocapitalize('words')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->placeholder('Masukkan Jabatan')
                    ->required(),
                TextInput::make('telepon')
                    ->label('Telepon')
                    ->placeholder('Masukkan Nomor Telepon')
                    ->numeric()
                    ->nullable()
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_user')
                    ->label('Kode User')
                    ->copyable()
                    ->sortable(),
                TextColumn::make('nama_user')
                    ->label('Nama User')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->copyable()
                    ->sortable(),
                TextColumn::make('telepon')
                    ->label('Telepon')
                    ->copyable()
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
            'index' => Pages\ListMasterUsers::route('/'),
            'create' => Pages\CreateMasterUser::route('/create'),
            'edit' => Pages\EditMasterUser::route('/{record}/edit'),
        ];
    }
}
