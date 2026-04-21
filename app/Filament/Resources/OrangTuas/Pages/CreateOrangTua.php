<?php

namespace App\Filament\Resources\OrangTuas\Pages;

use App\Filament\Resources\OrangTuas\OrangTuaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrangTua extends CreateRecord
{
    protected static string $resource = OrangTuaResource::class;

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
            $user->assignRole('Orang Tua');
            $data['user_id'] = $user->id;
        }
        return $data;
    }
}
