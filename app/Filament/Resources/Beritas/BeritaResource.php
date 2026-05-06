<?php

// Lokasi folder
namespace App\Filament\Resources\Beritas;

// Halaman
use App\Filament\Resources\Beritas\Pages\CreateBerita;
use App\Filament\Resources\Beritas\Pages\EditBerita;
use App\Filament\Resources\Beritas\Pages\ListBeritas;
use App\Filament\Resources\Beritas\Pages\ViewBerita;
// Form dan Tabel
use App\Filament\Resources\Beritas\Schemas\BeritaForm;
use App\Filament\Resources\Beritas\Tables\BeritasTable;
// Model Database
use App\Models\Berita;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * BeritaResource
 * 
 * Mengatur halaman menu "Berita".
 * Di sini Admin bisa menulis artikel berita atau pengumuman panjang
 * layaknya sebuah blog untuk diterbitkan di website sekolah.
 */
class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    // Ikon koran
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;
    
    // Dimasukkan ke kelompok menu Konten & Informasi
    protected static string|\UnitEnum|null $navigationGroup = 'Konten & Informasi';
    
    // Label halaman
    protected static ?string $modelLabel = 'Berita';
    protected static ?string $pluralModelLabel = 'Berita';

    /**
     * FUNGSI KEAMANAN
     * Guru dilarang menulis atau menghapus berita di website, hanya Admin/Kepsek.
     */
    public static function canAccess(): bool
    {
        $user = Auth::user(); 
        return $user && !$user->hasRole('Guru');
    }

    public static function form(Schema $schema): Schema
    {
        return BeritaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BeritasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBeritas::route('/'),
            'create' => CreateBerita::route('/create'),
            'view'   => ViewBerita::route('/{record}'),
            'edit'   => EditBerita::route('/{record}/edit'),
        ];
    }
}
