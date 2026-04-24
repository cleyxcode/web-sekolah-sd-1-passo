<?php

namespace App\Filament\Resources\Beritas;

use App\Filament\Resources\Beritas\Pages\CreateBerita;
use App\Filament\Resources\Beritas\Pages\EditBerita;
use App\Filament\Resources\Beritas\Pages\ListBeritas;
use App\Filament\Resources\Beritas\Pages\ViewBerita;
use App\Filament\Resources\Beritas\Schemas\BeritaForm;
use App\Filament\Resources\Beritas\Tables\BeritasTable;
use App\Models\Berita;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;
    protected static string|\UnitEnum|null $navigationGroup = 'Konten & Informasi';
    protected static ?string $modelLabel = 'Berita';
    protected static ?string $pluralModelLabel = 'Berita';

    public static function canAccess(): bool
    {
        $user = Auth::user(); return $user && !$user->hasRole('Guru');
    }
    public static function form(Schema $schema): Schema
    {
        return BeritaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BeritasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBeritas::route('/'),
            'create' => CreateBerita::route('/create'),
            'view'   => ViewBerita::route('/{record}'),
            'edit'   => EditBerita::route('/{record}/edit'),
        ];
    }
}
