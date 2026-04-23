<?php

namespace App\Filament\Resources\Gurus\Pages;

use App\Filament\Resources\Gurus\GuruResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGuru extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\Gurus\GuruResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = \App\Models\User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        ]);
        
        $user->assignRole('Guru');
        $data['user_id'] = $user->id;

        unset($data['email']);
        unset($data['password']);

        return $data;
    }
}
