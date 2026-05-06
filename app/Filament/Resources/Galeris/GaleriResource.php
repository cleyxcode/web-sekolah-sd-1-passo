<?php

// Lokasi folder
namespace App\Filament\Resources\Galeris;

// Halaman
use App\Filament\Resources\Galeris\Pages\CreateGaleri;
use App\Filament\Resources\Galeris\Pages\EditGaleri;
use App\Filament\Resources\Galeris\Pages\ListGaleris;
use App\Filament\Resources\Galeris\Pages\ViewGaleri;
// Form dan Tabel
use App\Filament\Resources\Galeris\Schemas\GaleriForm;
use App\Filament\Resources\Galeris\Tables\GalerisTable;
// Model Database
use App\Models\Galeri;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * GaleriResource
 * 
 * Mengatur halaman menu "Galeri".
 * Di sini Admin / Kepala Sekolah bisa mengunggah foto atau video dokumentasi
 * kegiatan sekolah untuk ditampilkan di halaman utama website.
 */
class GaleriResource extends Resource
{
    protected static ?string $model = Galeri::class;

    // Ikon bingkai foto
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;
    
    // Dimasukkan ke kelompok menu Konten & Informasi
    protected static string|\UnitEnum|null $navigationGroup = 'Konten & Informasi';
    
    // Label tombol dan nama menu
    protected static ?string $modelLabel = 'Galeri';
    protected static ?string $pluralModelLabel = 'Galeri';

    /**
     * FUNGSI KEAMANAN
     * Guru tidak boleh mengakses menu galeri ini, hanya Admin/Kepsek.
     */
    public static function canAccess(): bool
    {
        $user = Auth::user(); 
        return $user && !$user->hasRole('Guru');
    }

    public static function form(Schema $schema): Schema
    {
        return GaleriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalerisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGaleris::route('/'),
            'create' => CreateGaleri::route('/create'),
            'view'   => ViewGaleri::route('/{record}'),
            'edit'   => EditGaleri::route('/{record}/edit'),
        ];
    }
}
