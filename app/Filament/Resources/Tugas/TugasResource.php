<?php

namespace App\Filament\Resources\Tugas;

use App\Filament\Resources\Tugas\Pages\CreateTugas;
use App\Filament\Resources\Tugas\Pages\EditTugas;
use App\Filament\Resources\Tugas\Pages\ListTugas;
use App\Filament\Resources\Tugas\Schemas\TugasForm;
use App\Filament\Resources\Tugas\Tables\TugasTable;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Tugas as TugasModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TugasResource extends Resource
{
    protected static ?string $model = TugasModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?string $modelLabel = 'Tugas';
    protected static ?string $pluralModelLabel = 'Tugas';
    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return TugasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TugasTable::configure($table);
    }

    /**
     * Guru hanya melihat tugas di kelas yang ia wali.
     * Super Admin & Kepala Sekolah melihat semua.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user  = Auth::user();

        if ($user?->hasRole('Guru')) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
                $query->whereIn('kelas_id', $kelasIds);
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        return $query->with(['kelas', 'guru'])->latest();
    }

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
            'index'  => ListTugas::route('/'),
            'create' => CreateTugas::route('/create'),
            'edit'   => EditTugas::route('/{record}/edit'),
        ];
    }
}
