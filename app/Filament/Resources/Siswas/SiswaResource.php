<?php

namespace App\Filament\Resources\Siswas;

use App\Filament\Resources\Siswas\Pages\CreateSiswa;
use App\Filament\Resources\Siswas\Pages\EditSiswa;
use App\Filament\Resources\Siswas\Pages\ListSiswas;
use App\Filament\Resources\Siswas\Pages\ViewSiswa;
use App\Filament\Resources\Siswas\Schemas\SiswaForm;
use App\Filament\Resources\Siswas\Tables\SiswasTable;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?string $modelLabel = 'Siswa';
    protected static ?string $pluralModelLabel = 'Siswa';

    public static function form(Schema $schema): Schema
    {
        return SiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiswasTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user  = auth()->user();

        // Super admin bisa melihat semua data siswa
        if ($user->hasRole('Super Admin')) {
            return $query;
        }

        // Guru hanya melihat siswa dari kelas yang ia ampu (wali kelas)
        $guru = Guru::where('user_id', $user->id)->first();

        if ($guru) {
            // Ambil ID kelas yang gurunya adalah wali kelas
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)
                ->pluck('id');

            return $query->whereIn('kelas_id', $kelasIds);
        }

        // Jika user bukan super admin dan bukan guru yang terdaftar, tampilkan kosong
        return $query->whereRaw('0 = 1');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSiswas::route('/'),
            'create' => CreateSiswa::route('/create'),
            'view'   => ViewSiswa::route('/{record}'),
            'edit'   => EditSiswa::route('/{record}/edit'),
        ];
    }
}
