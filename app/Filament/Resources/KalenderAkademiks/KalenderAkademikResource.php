<?php

namespace App\Filament\Resources\KalenderAkademiks;

use App\Filament\Resources\KalenderAkademiks\Pages\CreateKalenderAkademik;
use App\Filament\Resources\KalenderAkademiks\Pages\EditKalenderAkademik;
use App\Filament\Resources\KalenderAkademiks\Pages\ListKalenderAkademiks;
use App\Filament\Resources\KalenderAkademiks\Schemas\KalenderAkademikForm;
use App\Filament\Resources\KalenderAkademiks\Tables\KalenderAkademiksTable;
use App\Models\KalenderAkademik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KalenderAkademikResource extends Resource
{
    protected static ?string $model = KalenderAkademik::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKalenderAkademiks::route('/'),
            'create' => CreateKalenderAkademik::route('/create'),
            'edit' => EditKalenderAkademik::route('/{record}/edit'),
        ];
    }
}
