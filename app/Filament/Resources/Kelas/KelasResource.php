<?php

// Lokasi folder
namespace App\Filament\Resources\Kelas;

// Halaman
use App\Filament\Resources\Kelas\Pages\CreateKelas;
use App\Filament\Resources\Kelas\Pages\EditKelas;
use App\Filament\Resources\Kelas\Pages\ListKelas;
// Form dan Tabel
use App\Filament\Resources\Kelas\Schemas\KelasForm;
use App\Filament\Resources\Kelas\Tables\KelasTable;
// Model Database
use App\Models\Kelas;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * KelasResource
 * 
 * Mengatur menu halaman "Kelas".
 * Di menu ini admin membuat ruangan-ruangan kelas 
 * dan menugaskan guru mana yang menjadi wali kelasnya.
 */
class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    // Ikon bangunan sekolah
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;
    
    // Dimasukkan ke menu Akademik
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    
    // Label tombol dan judul halaman
    protected static ?string $modelLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';

    public static function form(Schema $schema): Schema
    {
        return KelasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KelasTable::configure($table);
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
            'index' => ListKelas::route('/'),
            'create' => CreateKelas::route('/create'),
            'edit' => EditKelas::route('/{record}/edit'),
        ];
    }
}
