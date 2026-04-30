<?php

namespace App\Filament\Resources\Nilais;

use App\Filament\Resources\Nilais\Pages\CreateNilai;
use App\Filament\Resources\Nilais\Pages\EditNilai;
use App\Filament\Resources\Nilais\Pages\ListNilais;
use App\Filament\Resources\Nilais\Pages\RekapNilaiKelas;
use App\Filament\Resources\Nilais\Pages\ViewNilai;
use App\Filament\Resources\Nilais\Schemas\NilaiForm;
use App\Filament\Resources\Nilais\Tables\NilaisTable;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Nilai;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
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
     * Cek apakah user yang sedang login adalah Wali Kelas dari suatu kelas.
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
     * Filter query: Super Admin semua, Wali Kelas hanya kelasnya sendiri,
     * user lain tidak bisa melihat sama sekali.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user  = auth()->user();

        if ($user->hasRole('Super Admin')) {
            return $query;
        }

        // Kepala Sekolah — view only semua data
        if ($user->hasRole('Kepala Sekolah')) {
            return $query;
        }

        // Guru: hanya bisa melihat nilai dari kelas yang ia walikan
        $guru = Guru::where('user_id', $user->id)->first();
        if ($guru) {
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
            if ($kelasIds->isNotEmpty()) {
                return $query->whereIn('kelas_id', $kelasIds);
            }
        }

        // Tidak ada akses
        return $query->whereRaw('0 = 1');
    }

    /**
     * Hanya Wali Kelas dan Super Admin yang bisa membuat nilai.
     */
    public static function canCreate(): bool
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) return true;

        // Cek apakah user ini adalah wali kelas
        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) return false;

        return Kelas::where('wali_kelas_id', $guru->id)->exists();
    }

    /**
     * Hanya Wali Kelas dari kelas tersebut dan Super Admin yang bisa edit.
     */
    public static function canEdit($record): bool
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) return true;

        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) return false;

        // Cek apakah record ini milik kelas yang di-walikan user ini
        return Kelas::where('id', $record->kelas_id)
            ->where('wali_kelas_id', $guru->id)
            ->exists();
    }

    /**
     * Hanya Wali Kelas dari kelas tersebut dan Super Admin yang bisa hapus.
     */
    public static function canDelete($record): bool
    {
        return self::canEdit($record);
    }

    /**
     * Kepala Sekolah hanya bisa view, tidak bisa create/edit/delete.
     */
    public static function canView($record): bool
    {
        $user = Auth::user();
        return $user->hasRole('Super Admin')
            || $user->hasRole('Kepala Sekolah')
            || self::canEdit($record);
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
            'rekap-kelas' => RekapNilaiKelas::route('/rekap-kelas'),
            'view'        => ViewNilai::route('/{record}'),
            'edit'        => EditNilai::route('/{record}/edit'),
        ];
    }
}
