<?php

namespace App\Filament\Resources\KalenderAkademiks\Pages;

use App\Filament\Resources\KalenderAkademiks\KalenderAkademikResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKalenderAkademik extends ViewRecord
{
    protected static string $resource = KalenderAkademikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
