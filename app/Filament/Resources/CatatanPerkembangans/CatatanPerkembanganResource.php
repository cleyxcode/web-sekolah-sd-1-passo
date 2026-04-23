<?php

namespace App\Filament\Resources\CatatanPerkembangans;

use App\Filament\Resources\CatatanPerkembangans\Pages\CreateCatatanPerkembangan;
use App\Filament\Resources\CatatanPerkembangans\Pages\EditCatatanPerkembangan;
use App\Filament\Resources\CatatanPerkembangans\Pages\ListCatatanPerkembangans;
use App\Filament\Resources\CatatanPerkembangans\Pages\ViewCatatanPerkembangan;
use App\Filament\Resources\CatatanPerkembangans\Schemas\CatatanPerkembanganForm;
use App\Filament\Resources\CatatanPerkembangans\Schemas\CatatanPerkembanganInfolist;
use App\Filament\Resources\CatatanPerkembangans\Tables\CatatanPerkembangansTable;
use App\Models\CatatanPerkembangan;
use App\Models\Guru;
use App\Models\Kelas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CatatanPerkembanganResource extends Resource
{
    protected static ?string $model = CatatanPerkembangan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
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
     * Scope query: Guru hanya melihat catatan siswa di kelas yang ia wali.
     * Super Admin & Kepala Sekolah melihat semua.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user  = Auth::user();

        if ($user?->hasRole('Guru')) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                // Ambil ID kelas yang guru ini jadi wali kelas
                $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
                // Filter: hanya catatan untuk siswa di kelas tersebut
                $query->whereHas('siswa', fn ($q) => $q->whereIn('kelas_id', $kelasIds));
            } else {
                // Guru tidak punya profil guru → tidak bisa lihat apapun
                $query->whereRaw('0 = 1');
            }
        }

        return $query;
    }

    /**
     * Guru bisa mengakses halaman form (nanti list kelasnya difilter di dalam form).
     */
    public static function canCreate(): bool
    {
        return true;
    }

    public static function getRelations(): array
    {
        return [
            //
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
