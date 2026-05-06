<?php

// Alamat folder tempat file ini berada
namespace App\Filament\Resources\TahunAjarans;

// Mengimpor file-file pembentuk halaman (Pages)
use App\Filament\Resources\TahunAjarans\Pages\CreateTahunAjaran; // Halaman Tambah
use App\Filament\Resources\TahunAjarans\Pages\EditTahunAjaran;   // Halaman Edit
use App\Filament\Resources\TahunAjarans\Pages\ListTahunAjarans;  // Halaman Daftar (Tabel)
// Mengimpor file konfigurasi Form dan Tabel
use App\Filament\Resources\TahunAjarans\Schemas\TahunAjaranForm;
use App\Filament\Resources\TahunAjarans\Tables\TahunAjaransTable;
// Mengimpor Model database
use App\Models\TahunAjaran;
// Elemen bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * TahunAjaranResource
 * 
 * Kelas ini mengatur menu "Tahun Ajaran" di panel Admin.
 * Digunakan untuk mengelola daftar tahun ajaran (contoh: 2024/2025 Semester 1).
 */
class TahunAjaranResource extends Resource
{
    // Mengacu pada model TahunAjaran di database
    protected static ?string $model = TahunAjaran::class;

    // Ikon kalender di sidebar kiri
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    
    // Dimasukkan ke dalam kelompok menu "Sistem"
    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';
    
    // Label tombol / penamaan tunggal
    protected static ?string $modelLabel = 'Tahun Ajaran';
    
    // Label judul / penamaan jamak
    protected static ?string $pluralModelLabel = 'Tahun Ajaran';

    /**
     * Konfigurasi form pengisian saat tambah / edit data
     */
    public static function form(Schema $schema): Schema
    {
        // Alihkan pengaturan form ke file TahunAjaranForm.php
        return TahunAjaranForm::configure($schema);
    }

    /**
     * Konfigurasi kolom-kolom tabel pada daftar tahun ajaran
     */
    public static function table(Table $table): Table
    {
        // Alihkan pengaturan tabel ke file TahunAjaransTable.php
        return TahunAjaransTable::configure($table);
    }

    /**
     * Konfigurasi tabel relasi (contoh: memunculkan daftar kelas di dalam halaman edit tahun ajaran)
     * Saat ini dibiarkan kosong karena tidak ada tabel anak yang perlu ditampilkan di sini
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Menentukan link / URL halaman untuk resource ini
     */
    public static function getPages(): array
    {
        return [
            'index' => ListTahunAjarans::route('/'),              // Halaman beranda daftar tabel
            'create' => CreateTahunAjaran::route('/create'),      // Halaman buat baru
            'edit' => EditTahunAjaran::route('/{record}/edit'),   // Halaman edit dengan parameter ID
        ];
    }
}
