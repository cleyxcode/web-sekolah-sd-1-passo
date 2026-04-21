<?php

namespace App\Filament\Resources\Gurus\Pages;

use App\Filament\Resources\Gurus\GuruResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGuru extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\Gurus\GuruResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['user_id'])) {
            $email = strtolower(str_replace(' ', '', $data['nama'])) . rand(100, 999) . '@sekolah.com';
            $user = \App\Models\User::create([
                'name' => $data['nama'],
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'is_active' => true,
            ]);
            $user->assignRole('Guru');
            $data['user_id'] = $user->id;
        }
        return $data;
    }
}
