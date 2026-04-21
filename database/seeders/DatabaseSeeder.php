<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Super Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $roles = ['Super Admin', 'Kepala Sekolah', 'Guru', 'Orang Tua'];
        foreach ($roles as $roleName) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);
        }

        if (!$user->hasRole('Super Admin')) {
            $user->assignRole('Super Admin');
        }

        // Default Tahun Ajaran
        TahunAjaran::firstOrCreate(
            ['nama' => '2024/2025', 'semester' => '1'],
            [
                'is_active' => true,
                'tanggal_mulai' => '2024-07-15',
                'tanggal_selesai' => '2024-12-20',
            ]
        );
    }
}
