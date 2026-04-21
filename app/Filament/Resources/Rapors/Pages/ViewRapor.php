<?php

namespace App\Filament\Resources\Rapors\Pages;

use App\Filament\Resources\Rapors\RaporResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRapor extends ViewRecord
{
    protected static string $resource = RaporResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
