<?php

namespace App\Filament\Resources\SettingSekolahs;

use App\Filament\Resources\SettingSekolahs\Pages\CreateSettingSekolah;
use App\Filament\Resources\SettingSekolahs\Pages\EditSettingSekolah;
use App\Filament\Resources\SettingSekolahs\Pages\ListSettingSekolahs;
use App\Filament\Resources\SettingSekolahs\Schemas\SettingSekolahForm;
use App\Filament\Resources\SettingSekolahs\Tables\SettingSekolahsTable;
use App\Models\SettingSekolah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SettingSekolahResource extends Resource
{
    protected static ?string $model = SettingSekolah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SettingSekolahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SettingSekolahsTable::configure($table);
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
            'index' => EditSettingSekolah::route('/'),
        ];
    }
}
