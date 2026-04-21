<?php

namespace App\Filament\Resources\OrangTuas\Pages;

use App\Filament\Resources\OrangTuas\OrangTuaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrangTua extends EditRecord
{
    protected static string $resource = OrangTuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
