<?php

// Lokasi folder

namespace App\Filament\Resources\KalenderAkademiks;

// Halaman
use App\Filament\Resources\KalenderAkademiks\Pages\CreateKalenderAkademik;
use App\Filament\Resources\KalenderAkademiks\Pages\EditKalenderAkademik;
use App\Filament\Resources\KalenderAkademiks\Pages\ListKalenderAkademiks;
use App\Filament\Resources\KalenderAkademiks\Pages\ViewKalenderAkademik;
// Form dan Tabel
use App\Filament\Resources\KalenderAkademiks\Schemas\KalenderAkademikForm;
use App\Filament\Resources\KalenderAkademiks\Tables\KalenderAkademiksTable;
// Model Database
use App\Models\KalenderAkademik;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * KalenderAkademikResource
 *
 * Mengatur halaman menu "Kalender Akademik".
 * Di sini admin mencatat hari-hari penting seperti hari libur, ujian,
 * atau acara sekolah lainnya yang nanti bisa dilihat oleh siswa/orang tua di portal.
 */
class KalenderAkademikResource extends Resource
{
    protected static ?string $model = KalenderAkademik::class;

    // Ikon kalender hari
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    // Dimasukkan ke menu Sistem
    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    // Label tombol dan judul halaman
    protected static ?string $modelLabel = 'Kalender Akademik';

    protected static ?string $pluralModelLabel = 'Kalender Akademik';

    /**
     * FUNGSI KEAMANAN
     * Guru tidak boleh masuk ke menu ini. Hanya Admin/Kepsek yang boleh.
     */
    public static function canAccess(): bool
    {
        return ! Auth::user()?->hasRole('Guru');
    }

    public static function form(Schema $schema): Schema
    {
        return KalenderAkademikForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KalenderAkademiksTable::configure($table);
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
            'index' => ListKalenderAkademiks::route('/'),
            'create' => CreateKalenderAkademik::route('/create'),
            'view' => ViewKalenderAkademik::route('/{record}'),
            'edit' => EditKalenderAkademik::route('/{record}/edit'),
        ];
    }
}
