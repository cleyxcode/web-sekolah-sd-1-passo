<?php

// Lokasi folder
namespace App\Filament\Resources\Nilais;

// Halaman-halaman
use App\Filament\Resources\Nilais\Pages\CreateNilai;
use App\Filament\Resources\Nilais\Pages\EditNilai;
use App\Filament\Resources\Nilais\Pages\ListNilais;
use App\Filament\Resources\Nilais\Pages\RekapNilaiKelas;
use App\Filament\Resources\Nilais\Pages\ViewNilai;
// Form dan Tabel
use App\Filament\Resources\Nilais\Schemas\NilaiForm;
use App\Filament\Resources\Nilais\Tables\NilaisTable;
// Model Database
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Nilai;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * NilaiResource
 * 
 * Mengatur halaman pencatatan nilai siswa.
 * Menu ini sangat dibatasi hak aksesnya agar tidak sembarang guru
 * bisa mengubah nilai kelas orang lain.
 */
class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    // Ikon kertas ujian
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    
    // Dimasukkan ke menu Akademik
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?string $modelLabel = 'Nilai';
    protected static ?string $pluralModelLabel = 'Nilai';

    public static function form(Schema $schema): Schema
    {
        return NilaiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NilaisTable::configure($table);
    }

    /**
     * FUNGSI BANTUAN KECIL: Cek ID Kelas dari Wali Kelas
     * Digunakan untuk mengecek "Apakah guru yang login ini adalah wali kelas?"
     */
    private static function getWaliKelasId(): ?int
    {
        $user = Auth::user();
        if (!$user) return null;
        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) return null;
        return Kelas::where('wali_kelas_id', $guru->id)->value('id');
    }

    /**
     * FUNGSI KEAMANAN: FILTER TABEL
     * Menentukan data nilai punya siapa yang boleh dilihat di tabel.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user  = auth()->user();

        // 1. Super Admin bisa melihat SEMUA nilai dari semua kelas
        if ($user->hasRole('Super Admin')) {
            return $query;
        }

        // 2. Kepala Sekolah juga bisa melihat SEMUA nilai (sebagai laporan)
        if ($user->hasRole('Kepala Sekolah')) {
            return $query;
        }

        // 3. Guru: HANYA bisa melihat nilai dari kelas yang ia walikan
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru) {
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
            if ($kelasIds->isNotEmpty()) {
                return $query->whereIn('nilais.kelas_id', $kelasIds);
            }
        }

        // 4. Jika bukan siapa-siapa, sembunyikan semua data (0 = 1 artinya salah)
        return $query->whereRaw('0 = 1');
    }

    /**
     * FUNGSI KEAMANAN: HAK AKSES TOMBOL "TAMBAH"
     * Menentukan siapa yang boleh membuat data nilai baru.
     */
    public static function canCreate(): bool
    {
        $user = Auth::user();
        // Super admin selalu boleh
        if ($user->hasRole('Super Admin')) return true;

        // Cek apakah user ini adalah guru
        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) return false;

        // Guru HANYA boleh jika dia adalah WALI KELAS (ada namanya di kolom wali_kelas_id pada tabel Kelas)
        return Kelas::where('wali_kelas_id', $guru->id)->exists();
    }

    /**
     * FUNGSI KEAMANAN: HAK AKSES TOMBOL "EDIT"
     * Menentukan apakah baris nilai ini boleh diedit oleh user yang login.
     */
    public static function canEdit($record): bool
    {
        $user = Auth::user();
        // Super admin selalu boleh edit
        if ($user->hasRole('Super Admin')) return true;

        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) return false;

        // Cek apakah baris nilai ($record) ini milik kelas yang di-walikan oleh guru tersebut
        return Kelas::where('id', $record->kelas_id)
            ->where('wali_kelas_id', $guru->id)
            ->exists();
    }

    /**
     * FUNGSI KEAMANAN: HAK AKSES TOMBOL "HAPUS"
     * Sama aturannya dengan Edit.
     */
    public static function canDelete($record): bool
    {
        return self::canEdit($record);
    }

    /**
     * FUNGSI KEAMANAN: HAK AKSES TOMBOL "LIHAT DETAIL"
     * Kepala Sekolah hanya bisa "Lihat" (View), tidak bisa Edit apalagi Hapus.
     */
    public static function canView($record): bool
    {
        $user = Auth::user();
        return $user->hasRole('Super Admin')
            || $user->hasRole('Kepala Sekolah')
            || self::canEdit($record); // Wali kelas juga tentu bisa lihat
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'       => ListNilais::route('/'),
            'create'      => CreateNilai::route('/create'),
            'rekap-kelas' => RekapNilaiKelas::route('/rekap-kelas'), // Halaman spesial untuk melihat rekap nilai satu kelas penuh
            'view'        => ViewNilai::route('/{record}'),
            'edit'        => EditNilai::route('/{record}/edit'),
        ];
    }
}
