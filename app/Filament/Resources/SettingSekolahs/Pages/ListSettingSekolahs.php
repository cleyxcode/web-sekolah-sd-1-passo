<?php

namespace App\Filament\Resources\SettingSekolahs\Pages;

use App\Filament\Resources\SettingSekolahs\SettingSekolahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSettingSekolahs extends ListRecords
{
    protected static string $resource = SettingSekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
