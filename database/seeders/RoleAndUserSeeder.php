<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Guru;
use App\Models\Kelas;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan Resources/Modul yang ada sesuai nama model di Policy
        $resources = [
            'Berita', 'CatatanPerkembangan', 'DeskripsiKompetensi', 'Galeri', 
            'Guru', 'GuruMataPelajaran', 'JadwalPelajaran', 'KalenderAkademik', 
            'Kelas', 'MataPelajaran', 'Nilai', 'OrangTua', 'Pendaftaran', 
            'Presensi', 'ProfilSekolah', 'SettingSekolah', 'Siswa', 
            'TahunAjaran', 'User'
        ];

        // Hanya gunakan 5 aksi dasar agar tampilan checkbox di UI Filament tidak membuat admin awam bingung
        $actions = ['view-any', 'view', 'create', 'update', 'delete'];

        // 2. Buat semua Permissions
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $action . ' ' . $resource]);
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
            $kepsekPermissions[] = 'view-any ' . $resource;
            $kepsekPermissions[] = 'view ' . $resource;
        }
        $roleKepsek->syncPermissions($kepsekPermissions);

        // --- GURU ---
        // Guru punya akses CRUD ke nilai, presensi, catatan, dan view ke beberapa data induk
        $guruPermissions = [
            'view-any Siswa', 'view Siswa',
            'view-any JadwalPelajaran', 'view JadwalPelajaran',
            'view-any KalenderAkademik', 'view KalenderAkademik',
            // CRUD Nilai
            'view-any Nilai', 'view Nilai', 'create Nilai', 'update Nilai', 'delete Nilai',
            // CRUD Presensi
            'view-any Presensi', 'view Presensi', 'create Presensi', 'update Presensi', 'delete Presensi',
            // CRUD Catatan Perkembangan
            'view-any CatatanPerkembangan', 'view CatatanPerkembangan', 'create CatatanPerkembangan', 'update CatatanPerkembangan', 'delete CatatanPerkembangan',
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

        // Pastikan akun guru@sekolah.com memiliki profil di tabel Guru
        $guruProfile = Guru::firstOrCreate(
            ['user_id' => $userGuru->id],
            [
                'nip' => '198001012010011001',
                'nama' => 'Budi Santoso, S.Pd',
                'jenis_kelamin' => 'L',
                'no_telepon' => '081234567890'
            ]
        );

        // Assign guru ini sebagai wali kelas (contoh: kelas pertama) agar bisa mengisi data
        $kelas = Kelas::first();
        if ($kelas) {
            $kelas->update(['wali_kelas_id' => $guruProfile->id]);
        }
    }
}
