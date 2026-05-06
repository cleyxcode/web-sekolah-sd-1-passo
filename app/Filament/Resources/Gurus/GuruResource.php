<?php

// Lokasi folder
namespace App\Filament\Resources\Gurus;

// Halaman
use App\Filament\Resources\Gurus\Pages\CreateGuru;
use App\Filament\Resources\Gurus\Pages\EditGuru;
use App\Filament\Resources\Gurus\Pages\ListGurus;
// Form dan Tabel
use App\Filament\Resources\Gurus\Schemas\GuruForm;
use App\Filament\Resources\Gurus\Tables\GurusTable;
// Model Database
use App\Models\Guru;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * GuruResource
 * 
 * Mengatur halaman menu "Guru".
 * Di sini Admin mendaftarkan data pribadi para guru
 * sekaligus membuatkan akun login mereka untuk masuk ke dashboard ini.
 */
class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    // Ikon kumpulan orang banyak
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    
    // Dimasukkan ke kelompok Akademik
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    
    // Penamaan
    protected static ?string $modelLabel = 'Guru';
    protected static ?string $pluralModelLabel = 'Guru';

    public static function form(Schema $schema): Schema
    {
        return GuruForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GurusTable::configure($table);
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
            'index' => ListGurus::route('/'),
            'create' => CreateGuru::route('/create'),
            'edit' => EditGuru::route('/{record}/edit'),
        ];
    }
}
