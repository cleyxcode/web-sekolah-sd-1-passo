<?php

namespace App\Filament\Resources\Gurus\Pages;

use App\Filament\Resources\Gurus\GuruResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGuru extends EditRecord
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $guru = $this->getRecord();
        if ($guru->user) {
            $data['email'] = $guru->user->email;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $guru = $this->getRecord();
        if ($guru->user) {
            $userData = [
                'name' => $data['nama'],
                'email' => $data['email'],
            ];

            if (!empty($data['password'])) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
            }

            $guru->user->update($userData);
        }

        unset($data['email']);
        unset($data['password']);

        return $data;
    }
}
