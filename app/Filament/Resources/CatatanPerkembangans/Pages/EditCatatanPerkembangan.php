<?php

namespace App\Filament\Resources\CatatanPerkembangans\Pages;

use App\Filament\Resources\CatatanPerkembangans\CatatanPerkembanganResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCatatanPerkembangan extends EditRecord
{
    protected static string $resource = CatatanPerkembanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
