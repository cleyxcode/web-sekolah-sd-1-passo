<?php

// Lokasi folder

namespace App\Filament\Resources\OrangTuas;

// Halaman-halaman
use App\Filament\Resources\OrangTuas\Pages\CreateOrangTua;
use App\Filament\Resources\OrangTuas\Pages\EditOrangTua;
use App\Filament\Resources\OrangTuas\Pages\ListOrangTuas;
// Skema (Form) & Tabel
use App\Filament\Resources\OrangTuas\Schemas\OrangTuaForm;
use App\Filament\Resources\OrangTuas\Tables\OrangTuasTable;
// Model
use App\Models\OrangTua;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * OrangTuaResource
 *
 * Mengatur halaman menu "Data Orang Tua".
 * Di sini Admin bisa menambah akun portal untuk orang tua,
 * mengatur kata sandinya, serta menyambungkannya dengan data siswa anaknya.
 */
class OrangTuaResource extends Resource
{
    protected static ?string $model = OrangTua::class;

    // Ikon kumpulan pengguna di sidebar
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    // Dimasukkan ke kelompok "Data Master"
    protected static string|\UnitEnum|null $navigationGroup = 'Data Master';

    // Pencarian cepat berdasarkan nama orang tua
    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return OrangTuaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrangTuasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // Kosong
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrangTuas::route('/'),
            'create' => CreateOrangTua::route('/create'),
            'edit' => EditOrangTua::route('/{record}/edit'),
        ];
    }
}
