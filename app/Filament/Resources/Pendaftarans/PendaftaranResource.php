<?php

namespace App\Filament\Resources\Pendaftarans;

use App\Filament\Resources\Pendaftarans\Pages\CreatePendaftaran;
use App\Filament\Resources\Pendaftarans\Pages\EditPendaftaran;
use App\Filament\Resources\Pendaftarans\Pages\ListPendaftarans;
use App\Filament\Resources\Pendaftarans\Pages\ViewPendaftaran;
use App\Filament\Resources\Pendaftarans\Schemas\PendaftaranForm;
use App\Filament\Resources\Pendaftarans\Schemas\PendaftaranInfolist;
use App\Filament\Resources\Pendaftarans\Tables\PendaftaransTable;
use App\Models\Pendaftaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;
    protected static string|\UnitEnum|null $navigationGroup = 'Konten Website';
    protected static ?string $navigationLabel = 'Pendaftaran Siswa';
    protected static ?string $modelLabel = 'Data Pendaftaran';
    protected static ?string $pluralModelLabel = 'Data Pendaftaran';
    protected static ?string $recordTitleAttribute = 'judul';

    public static function canAccess(): bool
    {
        $user = Auth::user(); return $user && !$user->hasRole('Guru');
    }
    public static function form(Schema $schema): Schema
    {
        return PendaftaranForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PendaftaranInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PendaftaransTable::configure($table);
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
            'index' => ListPendaftarans::route('/'),
            'create' => CreatePendaftaran::route('/create'),
            'view' => ViewPendaftaran::route('/{record}'),
            'edit' => EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
