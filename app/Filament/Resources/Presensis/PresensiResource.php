<?php

// Lokasi folder

namespace App\Filament\Resources\Presensis;

// Halaman-halaman
use App\Filament\Resources\Presensis\Pages\CreatePresensi;
use App\Filament\Resources\Presensis\Pages\EditPresensi;
use App\Filament\Resources\Presensis\Pages\ListPresensis;
use App\Filament\Resources\Presensis\Pages\ViewPresensi;
// Form dan Tabel
use App\Filament\Resources\Presensis\Schemas\PresensiForm;
use App\Filament\Resources\Presensis\Tables\PresensisTable;
// Model
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Presensi;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * PresensiResource
 *
 * Mengatur halaman pencatatan absensi / kehadiran siswa tiap hari.
 */
class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;

    // Ikon papan ujian dengan tanda centang (clipboard document check)
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    // Dimasukkan ke menu Akademik
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    // Label nama di tombol
    protected static ?string $modelLabel = 'Presensi';

    protected static ?string $pluralModelLabel = 'Presensi';

    public static function form(Schema $schema): Schema
    {
        return PresensiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresensisTable::configure($table);
    }

    /**
     * FUNGSI FILTER DATA (Keamanan)
     * Mengatur siapa saja yang boleh melihat data absen ini.
     * Guru hanya boleh melihat presensi kelas yang ia wali.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Super Admin & Kepala Sekolah: lihat semua presensi
        if ($user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah')) {
            return $query;
        }

        // Wali Kelas: hanya bisa melihat presensi dari kelas yang ia wali
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru) {
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
            // Jika ada kelasnya, filter data presensi khusus untuk kelas itu saja
            if ($kelasIds->isNotEmpty()) {
                return $query->whereIn('presensis.kelas_id', $kelasIds);
            }
        }

        // Jika bukan siapa-siapa, sembunyikan semua data (0=1 itu salah/false)
        return $query->whereRaw('0 = 1');
    }

    /**
     * Mengizinkan Guru untuk bisa menekan tombol "Tambah Presensi"
     */
    public static function canCreate(): bool
    {
        return true;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPresensis::route('/'),              // Daftar absensi
            'create' => CreatePresensi::route('/create'),       // Catat absen baru
            'view' => ViewPresensi::route('/{record}'),       // Lihat detail absen
            'edit' => EditPresensi::route('/{record}/edit'),  // Edit absen
        ];
    }
}
