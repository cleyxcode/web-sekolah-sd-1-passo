<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAjaran;
use App\Models\MataPelajaran;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::firstOrCreate([
            'nama' => '2025/2026',
            'semester' => '1'
        ], [
            'is_active' => true
        ]);

        MataPelajaran::firstOrCreate([
            'nama' => 'Matematika'
        ], [
            'kode' => 'MAT01',
            'tingkat_kelas' => 1
        ]);
    }
}
