<?php

// Lokasi folder
namespace App\Filament\Resources\CatatanPerkembangans;

// Halaman-halaman
use App\Filament\Resources\CatatanPerkembangans\Pages\CreateCatatanPerkembangan;
use App\Filament\Resources\CatatanPerkembangans\Pages\EditCatatanPerkembangan;
use App\Filament\Resources\CatatanPerkembangans\Pages\ListCatatanPerkembangans;
use App\Filament\Resources\CatatanPerkembangans\Pages\ViewCatatanPerkembangan;
// Form, Tabel, dan Infolist (untuk halaman View)
use App\Filament\Resources\CatatanPerkembangans\Schemas\CatatanPerkembanganForm;
use App\Filament\Resources\CatatanPerkembangans\Schemas\CatatanPerkembanganInfolist;
use App\Filament\Resources\CatatanPerkembangans\Tables\CatatanPerkembangansTable;
// Model Database
use App\Models\CatatanPerkembangan;
use App\Models\Guru;
use App\Models\Kelas;
// Bawaan Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * CatatanPerkembanganResource
 * 
 * Mengatur halaman menu "Catatan Perkembangan".
 * Menu ini digunakan oleh guru untuk menulis catatan perilaku atau 
 * perkembangan siswa yang nantinya bisa dibaca oleh orang tua di portal mereka.
 */
class CatatanPerkembanganResource extends Resource
{
    protected static ?string $model = CatatanPerkembangan::class;

    // Ikon balon obrolan teks
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;
    
    // Dimasukkan ke menu Akademik
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    
    // Label tombol dan nama menu
    protected static ?string $modelLabel = 'Catatan Perkembangan';
    protected static ?string $pluralModelLabel = 'Catatan Perkembangan';

    protected static ?string $recordTitleAttribute = 'predikat';

    public static function form(Schema $schema): Schema
    {
        return CatatanPerkembanganForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CatatanPerkembanganInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CatatanPerkembangansTable::configure($table);
    }

    /**
     * FUNGSI KEAMANAN: FILTER TABEL (Membatasi data yang terlihat)
     * Guru HANYA boleh melihat catatan siswa di kelas yang ia walikan.
     * Super Admin & Kepala Sekolah boleh melihat semuanya.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user  = Auth::user();

        // 1. Super Admin & Kepala Sekolah: lihat semua catatan
        if ($user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah')) {
            return $query;
        }

        // 2. Wali Kelas: hanya catatan siswa di kelas yang ia wali
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru) {
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
            // Jika dia punya kelas, saring (filter) catatan HANYA untuk anak-anak di kelasnya itu
            if ($kelasIds->isNotEmpty()) {
                return $query->whereHas('siswa', fn ($q) => $q->whereIn('kelas_id', $kelasIds));
            }
        }

        // 3. Jika bukan siapa-siapa, sembunyikan (0=1 itu artinya salah/jangan tampilkan apapun)
        return $query->whereRaw('0 = 1');
    }

    /**
     * Guru diizinkan untuk membuat catatan. 
     * (Nanti pemilihan siswanya akan dikunci hanya untuk muridnya sendiri di dalam form).
     */
    public static function canCreate(): bool
    {
        return true;
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
            'index' => ListCatatanPerkembangans::route('/'),
            'create' => CreateCatatanPerkembangan::route('/create'),
            'view' => ViewCatatanPerkembangan::route('/{record}'),
            'edit' => EditCatatanPerkembangan::route('/{record}/edit'),
        ];
    }
}
