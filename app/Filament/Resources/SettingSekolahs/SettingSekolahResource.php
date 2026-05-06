<?php

// Lokasi folder
namespace App\Filament\Resources\SettingSekolahs;

// Halaman-halaman
use App\Filament\Resources\SettingSekolahs\Pages\CreateSettingSekolah;
use App\Filament\Resources\SettingSekolahs\Pages\EditSettingSekolah;
use App\Filament\Resources\SettingSekolahs\Pages\ListSettingSekolahs;
// Form dan Tabel
use App\Filament\Resources\SettingSekolahs\Schemas\SettingSekolahForm;
use App\Filament\Resources\SettingSekolahs\Tables\SettingSekolahsTable;
// Model Database
use App\Models\SettingSekolah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * SettingSekolahResource
 * 
 * Mengatur halaman konfigurasi dasar sekolah (Nama, Logo, Alamat, dll).
 * Biasanya tabel ini hanya berisi SATU baris data saja.
 */
class SettingSekolahResource extends Resource
{
    protected static ?string $model = SettingSekolah::class;

    // Ikon gerigi (pengaturan)
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog8Tooth;
    
    // Dimasukkan ke grup Sistem
    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';
    
    // Label nama menu
    protected static ?string $modelLabel = 'Setting Sekolah';
    protected static ?string $pluralModelLabel = 'Setting Sekolah';

    public static function form(Schema $schema): Schema
    {
        return SettingSekolahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SettingSekolahsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // Kosong
        ];
    }

    /**
     * Karena pengaturan sekolah hanya 1 data, 
     * kita langsung arahkan halaman utamanya ke Edit (bukan daftar tabel)
     */
    public static function getPages(): array
    {
        return [
            // Saat diklik di menu, langsung masuk ke form Edit (tidak perlu lewat tabel)
            'index' => EditSettingSekolah::route('/'),
        ];
    }
}
