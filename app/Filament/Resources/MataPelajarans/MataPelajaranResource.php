<?php

// Lokasi folder

namespace App\Filament\Resources\MataPelajarans;

// Halaman
use App\Filament\Resources\MataPelajarans\Pages\CreateMataPelajaran;
use App\Filament\Resources\MataPelajarans\Pages\EditMataPelajaran;
use App\Filament\Resources\MataPelajarans\Pages\ListMataPelajarans;
// Form dan Tabel
use App\Filament\Resources\MataPelajarans\Schemas\MataPelajaranForm;
use App\Filament\Resources\MataPelajarans\Tables\MataPelajaransTable;
// Model Database
use App\Models\MataPelajaran;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * MataPelajaranResource
 *
 * Mengatur halaman menu "Mata Pelajaran".
 * Di sini Admin bisa mendata daftar pelajaran yang ada di sekolah (Matematika, IPA, dll).
 */
class MataPelajaranResource extends Resource
{
    protected static ?string $model = MataPelajaran::class;

    // Ikon buku terbuka
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    // Dimasukkan ke menu Akademik
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    // Label nama
    protected static ?string $modelLabel = 'Mata Pelajaran';

    protected static ?string $pluralModelLabel = 'Mata Pelajaran';

    public static function form(Schema $schema): Schema
    {
        return MataPelajaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MataPelajaransTable::configure($table);
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
            'index' => ListMataPelajarans::route('/'),
            'create' => CreateMataPelajaran::route('/create'),
            'edit' => EditMataPelajaran::route('/{record}/edit'),
        ];
    }
}
