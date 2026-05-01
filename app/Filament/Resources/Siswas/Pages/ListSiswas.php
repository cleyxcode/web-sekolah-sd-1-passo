<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Actions\NaikKelasAction;
use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            NaikKelasAction::make()
                ->outlined()
                ->size('md'),
            CreateAction::make(),
        ];
    }
}
