<?php

// Menentukan alamat folder tempat file ini berada

namespace App\Filament\Resources\Tugas;

// Mengimpor kelas halaman-halaman (Pages) yang mengatur tampilan form dan daftar tabel
use App\Filament\Resources\Tugas\Pages\CreateTugas; // Halaman membuat tugas baru
use App\Filament\Resources\Tugas\Pages\EditTugas;   // Halaman edit tugas
use App\Filament\Resources\Tugas\Pages\ListTugas;   // Halaman daftar tabel tugas
// Mengimpor konfigurasi Formulir dan Tabel yang dipisah agar rapi
use App\Filament\Resources\Tugas\Schemas\TugasForm;
use App\Filament\Resources\Tugas\Tables\TugasTable;
// Mengimpor model-model database yang dibutuhkan
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Tugas as TugasModel; // Di-alias menjadi TugasModel agar namanya tidak bertabrakan dengan class TugasResource
// Impor bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

 // Untuk cek siapa yang sedang login

/**
 * TugasResource
 *
 * Kelas ini mengatur menu "Tugas" di panel Admin Filament.
 * Di sinilah guru bisa menambahkan PR/tugas untuk siswa,
 * yang nanti akan muncul di HP / Portal Orang Tua.
 */
class TugasResource extends Resource
{
    // Menyambungkan menu ini dengan tabel 'tugas' di database
    protected static ?string $model = TugasModel::class;

    // Menentukan ikon buku catatan (clipboard) di menu sidebar kiri
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    // Mengelompokkan menu ini ke dalam kategori "Akademik"
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    // Nama tunggal (misal untuk tombol: "Tambah Tugas")
    protected static ?string $modelLabel = 'Tugas';

    // Nama jamak (misal untuk judul halaman: "Daftar Tugas")
    protected static ?string $pluralModelLabel = 'Tugas';

    // Mengatur urutan menu ini di sidebar (posisi ke-5 dari atas)
    protected static ?int $navigationSort = 5;

    // Menentukan kolom mana yang menjadi "judul utama" saat melakukan pencarian cepat (Global Search)
    protected static ?string $recordTitleAttribute = 'judul';

    /**
     * Konfigurasi bentuk formulir saat membuat/edit tugas
     */
    public static function form(Schema $schema): Schema
    {
        // Alihkan pengaturannya ke file TugasForm.php
        return TugasForm::configure($schema);
    }

    /**
     * Konfigurasi daftar kolom tabel tugas
     */
    public static function table(Table $table): Table
    {
        // Alihkan pengaturannya ke file TugasTable.php
        return TugasTable::configure($table);
    }

    /**
     * FUNGSI FILTER DATA (Keamanan & Hak Akses)
     * Mengatur tugas siapa saja yang boleh dilihat oleh pengguna yang login.
     *
     * Aturan:
     * - Guru HANYA boleh melihat tugas dari kelas yang dia wali.
     * - Super Admin & Kepala Sekolah boleh melihat SEMUA tugas dari seluruh kelas.
     */
    public static function getEloquentQuery(): Builder
    {
        // Ambil cara asli bawaan dari sistem
        $query = parent::getEloquentQuery();
        // Cek siapa yang sedang login
        $user = Auth::user();

        // 1. Jika yang login adalah Super Admin ATAU Kepala Sekolah
        if ($user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah')) {
            // Berikan semua data tugas tanpa difilter, urutkan dari yang terbaru (latest)
            // with() digunakan agar loading data tabel lebih cepat
            return $query->with(['kelas', 'guru'])->latest();
        }

        // 2. Jika yang login BUKAN admin, berarti kemungkinan besar adalah Guru/Wali Kelas
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru) {
            // Cari ID kelas apa saja yang mana guru ini bertugas sebagai wali kelas
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');

            // Jika guru ini benar-benar punya kelas perwalian...
            if ($kelasIds->isNotEmpty()) {
                // ...maka TAMPILKAN HANYA tugas yang kelas_id-nya cocok dengan kelas perwaliannya
                return $query->whereIn('tugas.kelas_id', $kelasIds)->with(['kelas', 'guru'])->latest();
            }
        }

        // 3. Jika bukan siapa-siapa (atau guru yang tidak punya kelas), maka sembunyikan semua data (0 = 1 bernilai FALSE)
        return $query->whereRaw('0 = 1');
    }

    /**
     * Mengatur tabel turunan (misalnya daftar komentar di dalam detail tugas)
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Mengatur halaman dan link URL
     */
    public static function getPages(): array
    {
        return [
            'index' => ListTugas::route('/'),              // Halaman awal
            'create' => CreateTugas::route('/create'),      // Halaman tambah tugas
            'edit' => EditTugas::route('/{record}/edit'), // Halaman edit tugas
        ];
    }
}
