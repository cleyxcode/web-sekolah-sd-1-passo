<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function afterCreate(): void
    {
        $siswa = $this->record;
        
        // Jika form 'orangTuas' dibiarkan kosong, buatkan otomatis
        if ($siswa->orangTuas()->count() === 0) {
            $email = 'ortu.' . $siswa->nis . '@sekolah.com';
            $ortu = \App\Models\OrangTua::firstOrCreate(
                ['email' => $email],
                [
                    'nama' => 'Wali dari ' . $siswa->nama,
                    'password' => \Illuminate\Support\Facades\Hash::make($siswa->nis),
                ]
            );
            $siswa->orangTuas()->attach($ortu->id);
        }
    }
}
