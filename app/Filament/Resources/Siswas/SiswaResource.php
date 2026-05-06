<?php

// Lokasi folder tempat file ini berada
namespace App\Filament\Resources\Siswas;

// Mengimpor file-file halaman khusus siswa
use App\Filament\Resources\Siswas\Pages\CreateSiswa; // Halaman tambah
use App\Filament\Resources\Siswas\Pages\EditSiswa;   // Halaman edit
use App\Filament\Resources\Siswas\Pages\ListSiswas;  // Halaman daftar tabel
use App\Filament\Resources\Siswas\Pages\ViewSiswa;   // Halaman lihat detail
// Mengimpor file Form dan Tabel
use App\Filament\Resources\Siswas\Schemas\SiswaForm;
use App\Filament\Resources\Siswas\Tables\SiswasTable;
// Mengimpor Model
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * SiswaResource
 * 
 * Kelas induk penghubung untuk mengelola data "Siswa" di panel Admin Filament.
 */
class SiswaResource extends Resource
{
    // Terhubung ke model Siswa
    protected static ?string $model = Siswa::class;

    // Ikon topi wisuda di sidebar
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    
    // Kelompok menu Akademik
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    
    // Penamaan data di tombol / judul
    protected static ?string $modelLabel = 'Siswa';
    protected static ?string $pluralModelLabel = 'Siswa';

    /**
     * Menyusun kotak isian (Form)
     */
    public static function form(Schema $schema): Schema
    {
        // Alihkan ke SiswaForm.php
        return SiswaForm::configure($schema);
    }

    /**
     * Menyusun daftar kolom (Tabel)
     */
    public static function table(Table $table): Table
    {
        // Alihkan ke SiswasTable.php
        return SiswasTable::configure($table);
    }

    /**
     * FUNGSI FILTER DATA (Keamanan & Hak Akses)
     * Mengatur data siswa siapa saja yang boleh dilihat oleh pengguna yang login.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user  = auth()->user();

        // 1. Super Admin & Kepala Sekolah bisa melihat SEMUA data siswa dari seluruh kelas
        if ($user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah')) {
            return $query; // Kembalikan query tanpa difilter
        }

        // 2. Wali Kelas hanya melihat data siswa dari kelas yang ia wali saja
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru) {
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
            // Jika guru punya kelas perwalian, saring data siswa berdasarkan ID kelasnya
            if ($kelasIds->isNotEmpty()) {
                return $query->whereIn('siswas.kelas_id', $kelasIds);
            }
        }

        // 3. Jika bukan siapa-siapa, sembunyikan semua data
        return $query->whereRaw('0 = 1');
    }

    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Mengatur rute URL halaman
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListSiswas::route('/'),              // Halaman awal daftar tabel
            'create' => CreateSiswa::route('/create'),      // Halaman tambah data
            'view'   => ViewSiswa::route('/{record}'),      // Halaman lihat detail (ReadOnly)
            'edit'   => EditSiswa::route('/{record}/edit'), // Halaman ubah data
        ];
    }
}
