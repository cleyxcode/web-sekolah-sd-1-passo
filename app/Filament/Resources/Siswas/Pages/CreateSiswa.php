<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Models\OrangTua;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function afterCreate(): void
    {
        $siswa = $this->record;

        // Jika form 'orangTuas' dibiarkan kosong, buatkan otomatis
        if ($siswa->orangTuas()->count() === 0) {
            $email = 'ortu.'.$siswa->nis.'@sekolah.com';
            $ortu = OrangTua::firstOrCreate(
                ['email' => $email],
                [
                    'nama' => 'Wali dari '.$siswa->nama,
                    'password' => Hash::make($siswa->nis),
                ]
            );
            $siswa->orangTuas()->attach($ortu->id);
        }
    }
}
