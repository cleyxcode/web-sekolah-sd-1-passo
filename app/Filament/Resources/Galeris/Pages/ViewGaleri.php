<?php

namespace App\Filament\Resources\Galeris\Pages;

use App\Filament\Resources\Galeris\GaleriResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGaleri extends ViewRecord
{
    protected static string $resource = GaleriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
