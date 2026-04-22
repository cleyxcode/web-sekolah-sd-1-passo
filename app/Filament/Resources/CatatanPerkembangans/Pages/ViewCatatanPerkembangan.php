<?php

namespace App\Filament\Resources\CatatanPerkembangans\Pages;

use App\Filament\Resources\CatatanPerkembangans\CatatanPerkembanganResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCatatanPerkembangan extends ViewRecord
{
    protected static string $resource = CatatanPerkembanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
