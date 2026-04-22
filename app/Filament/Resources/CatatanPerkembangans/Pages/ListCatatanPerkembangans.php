<?php

namespace App\Filament\Resources\CatatanPerkembangans\Pages;

use App\Filament\Resources\CatatanPerkembangans\CatatanPerkembanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCatatanPerkembangans extends ListRecords
{
    protected static string $resource = CatatanPerkembanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
