<?php

namespace App\Filament\Resources\Presensis;

use App\Filament\Resources\Presensis\Pages\CreatePresensi;
use App\Filament\Resources\Presensis\Pages\EditPresensi;
use App\Filament\Resources\Presensis\Pages\ListPresensis;
use App\Filament\Resources\Presensis\Pages\ViewPresensi;
use App\Filament\Resources\Presensis\Schemas\PresensiForm;
use App\Filament\Resources\Presensis\Tables\PresensisTable;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Presensi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?string $modelLabel = 'Presensi';
    protected static ?string $pluralModelLabel = 'Presensi';

    public static function form(Schema $schema): Schema
    {
        return PresensiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresensisTable::configure($table);
    }

    /**
     * Scope: Guru hanya melihat presensi kelas yang ia wali.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user?->hasRole('Guru')) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
                $query->whereIn('kelas_id', $kelasIds);
            } else {
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPresensis::route('/'),
            'create' => CreatePresensi::route('/create'),
            'view' => ViewPresensi::route('/{record}'),
            'edit' => EditPresensi::route('/{record}/edit'),
        ];
    }
}
