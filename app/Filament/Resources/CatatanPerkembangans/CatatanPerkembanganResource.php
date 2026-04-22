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
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery();
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
