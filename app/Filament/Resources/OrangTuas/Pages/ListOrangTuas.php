<?php

namespace App\Filament\Resources\OrangTuas\Pages;

use App\Filament\Resources\OrangTuas\OrangTuaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrangTuas extends ListRecords
{
    protected static string $resource = OrangTuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
