<?php

// Lokasi folder

namespace App\Filament\Resources\ProfilSekolahs;

// Halaman-halaman
use App\Filament\Resources\ProfilSekolahs\Pages\CreateProfilSekolah;
use App\Filament\Resources\ProfilSekolahs\Pages\EditProfilSekolah;
use App\Filament\Resources\ProfilSekolahs\Pages\ListProfilSekolahs;
// Form dan Tabel
use App\Filament\Resources\ProfilSekolahs\Schemas\ProfilSekolahForm;
use App\Filament\Resources\ProfilSekolahs\Tables\ProfilSekolahsTable;
// Model
use App\Models\ProfilSekolah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * ProfilSekolahResource
 *
 * Mengatur halaman konten informasi sekolah, seperti:
 * Sejarah, Visi, Misi, dan Kata Sambutan Kepala Sekolah.
 * Konten ini akan ditampilkan di website publik sekolah.
 */
class ProfilSekolahResource extends Resource
{
    protected static ?string $model = ProfilSekolah::class;

    // Ikon bangunan kantor
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    // Dimasukkan ke dalam menu "Konten & Informasi"
    protected static string|\UnitEnum|null $navigationGroup = 'Konten & Informasi';

    // Label tombol dan judul halaman
    protected static ?string $modelLabel = 'Profil Sekolah';

    protected static ?string $pluralModelLabel = 'Profil Sekolah';

    public static function form(Schema $schema): Schema
    {
        // Alihkan susunan form ke ProfilSekolahForm
        return ProfilSekolahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // Alihkan susunan tabel ke ProfilSekolahsTable
        return ProfilSekolahsTable::configure($table);
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
            // Rute standar Filament: Daftar, Tambah, dan Edit
            'index' => ListProfilSekolahs::route('/'),
            'create' => CreateProfilSekolah::route('/create'),
            'edit' => EditProfilSekolah::route('/{record}/edit'),
        ];
    }
}
