<?php

// Lokasi folder

namespace App\Filament\Resources\Pendaftarans;

// Halaman-halaman Filament
use App\Filament\Resources\Pendaftarans\Pages\CreatePendaftaran;
use App\Filament\Resources\Pendaftarans\Pages\EditPendaftaran;
use App\Filament\Resources\Pendaftarans\Pages\ListPendaftarans;
use App\Filament\Resources\Pendaftarans\Pages\ViewPendaftaran;
// Konfigurasi Form, Tabel, dan Infolist (tampilan detail)
use App\Filament\Resources\Pendaftarans\Schemas\PendaftaranForm;
use App\Filament\Resources\Pendaftarans\Schemas\PendaftaranInfolist;
use App\Filament\Resources\Pendaftarans\Tables\PendaftaransTable;
// Model Database
use App\Models\Pendaftaran;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * PendaftaranResource
 *
 * Mengatur halaman menu "Pendaftaran Siswa".
 * Menu ini digunakan untuk membuat link/pengumuman penerimaan murid baru
 * yang akan ditampilkan di halaman utama website sekolah.
 */
class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    // Ikon rantai (link) di sidebar
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    // Dimasukkan ke kelompok menu "Konten Website"
    protected static string|\UnitEnum|null $navigationGroup = 'Konten Website';

    // Nama yang muncul di sidebar menu
    protected static ?string $navigationLabel = 'Pendaftaran Siswa';

    // Label tombol dan judul halaman
    protected static ?string $modelLabel = 'Data Pendaftaran';

    protected static ?string $pluralModelLabel = 'Data Pendaftaran';

    // Kolom pencarian utama menggunakan judul pendaftaran
    protected static ?string $recordTitleAttribute = 'judul';

    /**
     * FUNGSI KEAMANAN
     * Hanya Admin / Kepala Sekolah yang boleh membuka menu ini.
     * Guru tidak boleh membuat atau melihat daftar pendaftaran siswa baru.
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();

        // Kembalikan nilai benar (true) jika user ada DAN bukan berstatus 'Guru'
        return $user && ! $user->hasRole('Guru');
    }

    public static function form(Schema $schema): Schema
    {
        return PendaftaranForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PendaftaranInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PendaftaransTable::configure($table);
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
            'index' => ListPendaftarans::route('/'),
            'create' => CreatePendaftaran::route('/create'),
            'view' => ViewPendaftaran::route('/{record}'),
            'edit' => EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
