<?php

namespace App\Filament\Resources\Beritas\Pages;

use App\Filament\Resources\Beritas\BeritaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBerita extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\Beritas\BeritaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }
        return $data;
    }
}
