<?php

namespace App\Filament\Resources\Galeris\Pages;

use App\Filament\Resources\Galeris\GaleriResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGaleri extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\Galeris\GaleriResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }
        return $data;
    }
}
