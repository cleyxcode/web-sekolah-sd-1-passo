<?php

// Lokasi folder
namespace App\Filament\Resources\JadwalPelajarans;

// Halaman-halaman
use App\Filament\Resources\JadwalPelajarans\Pages\CreateJadwalPelajaran;
use App\Filament\Resources\JadwalPelajarans\Pages\EditJadwalPelajaran;
use App\Filament\Resources\JadwalPelajarans\Pages\ListJadwalPelajarans;
// Form dan Tabel
use App\Filament\Resources\JadwalPelajarans\Schemas\JadwalPelajaranForm;
use App\Filament\Resources\JadwalPelajarans\Tables\JadwalPelajaransTable;
// Model Database
use App\Models\JadwalPelajaran;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * JadwalPelajaranResource
 * 
 * Mengatur halaman menu "Jadwal Pelajaran".
 * Berfungsi untuk menentukan di hari apa, jam berapa, guru siapa
 * yang mengajar pelajaran tertentu di suatu kelas.
 */
class JadwalPelajaranResource extends Resource
{
    protected static ?string $model = JadwalPelajaran::class;

    // Ikon kalender dinding
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;
    
    // Dimasukkan ke menu Akademik
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    
    // Nama menu dan judul
    protected static ?string $modelLabel = 'Jadwal Pelajaran';
    protected static ?string $pluralModelLabel = 'Jadwal Pelajaran';

    public static function form(Schema $schema): Schema
    {
        return JadwalPelajaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalPelajaransTable::configure($table);
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
            'index' => ListJadwalPelajarans::route('/'),
            'create' => CreateJadwalPelajaran::route('/create'),
            'edit' => EditJadwalPelajaran::route('/{record}/edit'),
        ];
    }
}
