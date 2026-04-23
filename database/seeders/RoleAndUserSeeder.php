<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan Resources/Modul yang ada
        $resources = [
            'berita', 'catatan_perkembangan', 'deskripsi_kompetensi', 'galeri', 
            'guru', 'guru_mata_pelajaran', 'jadwal_pelajaran', 'kalender_akademik', 
            'kelas', 'mata_pelajaran', 'nilai', 'orang_tua', 'pendaftaran', 
            'presensi', 'profil_sekolah', 'setting_sekolah', 'siswa', 
            'tahun_ajaran', 'user'
        ];

        $actions = ['view_any', 'view', 'create', 'update', 'delete'];

        // 2. Buat semua Permissions
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $action . '_' . $resource]);
            }
        }

        // Permission khusus dashboard statistik
        Permission::firstOrCreate(['name' => 'view_dashboard_stats']);

        // 3. Buat Roles
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $roleKepsek = Role::firstOrCreate(['name' => 'Kepala Sekolah']);
        $roleGuru = Role::firstOrCreate(['name' => 'Guru']);

        // 4. Assign Permissions ke Peran (Selain Super Admin yang punya bypass)
        
        // --- KEPALA SEKOLAH (Read-Only ke semua + bisa lihat statistik dashboard) ---
        $kepsekPermissions = ['view_dashboard_stats'];
        foreach ($resources as $resource) {
            $kepsekPermissions[] = 'view_any_' . $resource;
            $kepsekPermissions[] = 'view_' . $resource;
        }
        $roleKepsek->syncPermissions($kepsekPermissions);

        // --- GURU ---
        // Guru punya akses CRUD ke nilai, presensi, catatan, dan view ke beberapa data induk
        $guruPermissions = [
            'view_any_siswa', 'view_siswa',
            'view_any_jadwal_pelajaran', 'view_jadwal_pelajaran',
            'view_any_kalender_akademik', 'view_kalender_akademik',
            // CRUD Nilai
            'view_any_nilai', 'view_nilai', 'create_nilai', 'update_nilai', 'delete_nilai',
            // CRUD Presensi
            'view_any_presensi', 'view_presensi', 'create_presensi', 'update_presensi', 'delete_presensi',
            // CRUD Catatan Perkembangan
            'view_any_catatan_perkembangan', 'view_catatan_perkembangan', 'create_catatan_perkembangan', 'update_catatan_perkembangan', 'delete_catatan_perkembangan',
        ];
        $roleGuru->syncPermissions($guruPermissions);

        // 5. Buat Akun Users dan Pasangkan Role-nya
        $admin = User::firstOrCreate(
            ['email' => 'admin@sekolah.com'], 
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
        if (!$admin->hasRole('Super Admin')) $admin->assignRole('Super Admin');

        $userKepsek = User::firstOrCreate(
            ['email' => 'kepsek@sekolah.com'],
            ['name' => 'Bpk/Ibu Kepala Sekolah', 'password' => Hash::make('password')]
        );
        if (!$userKepsek->hasRole('Kepala Sekolah')) $userKepsek->assignRole('Kepala Sekolah');

        $userGuru = User::firstOrCreate(
            ['email' => 'guru@sekolah.com'], 
            ['name' => 'Budi Santoso, S.Pd', 'password' => Hash::make('password')]
        );
        if (!$userGuru->hasRole('Guru')) $userGuru->assignRole('Guru');
    }
}
