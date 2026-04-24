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
        // =====================================================================
        // 1. DEFINISI RESOURCES & ACTIONS
        // =====================================================================

        // Resource akademik — hanya dikelola guru/admin penuh
        $resourcesAkademik = [
            'CatatanPerkembangan', 'DeskripsiKompetensi', 'Guru',
            'GuruMataPelajaran', 'JadwalPelajaran', 'KalenderAkademik',
            'Kelas', 'MataPelajaran', 'Nilai', 'OrangTua',
            'Presensi', 'Siswa', 'TahunAjaran', 'User',
        ];

        // Resource konten website — dikelola Admin Konten
        $resourcesKonten = [
            'Berita', 'Galeri', 'Pendaftaran', 'ProfilSekolah', 'SettingSekolah',
        ];

        $allResources = array_merge($resourcesAkademik, $resourcesKonten);
        $actions = ['view-any', 'view', 'create', 'update', 'delete'];

        // =====================================================================
        // 2. BUAT SEMUA PERMISSIONS
        // =====================================================================
        foreach ($allResources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $action . ' ' . $resource]);
            }
        }

        // Permission khusus — dashboard widget statistik
        // Pisahkan agar bisa diberikan per role secara fleksibel
        Permission::firstOrCreate(['name' => 'view_dashboard_stats']);      // Statistik angka (total siswa, guru, dll)
        Permission::firstOrCreate(['name' => 'view_dashboard_chart']);      // Grafik kehadiran & nilai
        Permission::firstOrCreate(['name' => 'view_dashboard_recent']);     // Widget berita terbaru

        // =====================================================================
        // 3. BUAT ROLES
        // =====================================================================
        $roleSuperAdmin  = Role::firstOrCreate(['name' => 'Super Admin']);
        $roleKepsek      = Role::firstOrCreate(['name' => 'Kepala Sekolah']);
        $roleGuru        = Role::firstOrCreate(['name' => 'Guru']);
        $roleAdminKonten = Role::firstOrCreate(['name' => 'Admin Konten']);

        // =====================================================================
        // 4. ASSIGN PERMISSIONS
        // =====================================================================

        // --- KEPALA SEKOLAH ---
        // Read-only ke semua resource + semua widget statistik
        $kepsekPermissions = [
            'view_dashboard_stats',
            'view_dashboard_chart',
            'view_dashboard_recent',
        ];
        foreach ($allResources as $resource) {
            $kepsekPermissions[] = 'view-any ' . $resource;
            $kepsekPermissions[] = 'view ' . $resource;
        }
        $roleKepsek->syncPermissions($kepsekPermissions);

        // --- GURU ---
        // Akses CRUD nilai, presensi, catatan + view data induk + widget recent berita
        $guruPermissions = [
            'view_dashboard_recent',  // hanya lihat widget berita terbaru
            // Lihat data siswa & jadwal
            'view-any Siswa', 'view Siswa',
            'view-any JadwalPelajaran', 'view JadwalPelajaran',
            'view-any KalenderAkademik', 'view KalenderAkademik',
            'view-any MataPelajaran', 'view MataPelajaran',
            'view-any Kelas', 'view Kelas',
            // CRUD Nilai
            'view-any Nilai', 'view Nilai', 'create Nilai', 'update Nilai', 'delete Nilai',
            // CRUD Presensi
            'view-any Presensi', 'view Presensi', 'create Presensi', 'update Presensi', 'delete Presensi',
            // CRUD Catatan Perkembangan
            'view-any CatatanPerkembangan', 'view CatatanPerkembangan',
            'create CatatanPerkembangan', 'update CatatanPerkembangan', 'delete CatatanPerkembangan',
        ];
        $roleGuru->syncPermissions($guruPermissions);

        // --- ADMIN KONTEN ---
        // CRUD konten website saja, tidak bisa akses data akademik sensitif
        // Bisa lihat widget berita terbaru & statistik dasar
        $adminKontenPermissions = [
            'view_dashboard_stats',   // bisa lihat jumlah total siswa/guru (info publik)
            'view_dashboard_recent',  // widget berita terbaru
            // CRUD semua resource konten website
        ];
        foreach ($resourcesKonten as $resource) {
            $adminKontenPermissions[] = 'view-any ' . $resource;
            $adminKontenPermissions[] = 'view ' . $resource;
            $adminKontenPermissions[] = 'create ' . $resource;
            $adminKontenPermissions[] = 'update ' . $resource;
            $adminKontenPermissions[] = 'delete ' . $resource;
        }
        $roleAdminKonten->syncPermissions($adminKontenPermissions);

        // =====================================================================
        // 5. BUAT AKUN USERS DEMO & PASANGKAN ROLE
        // =====================================================================

        // Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@sekolah.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
        if (!$admin->hasRole('Super Admin')) $admin->assignRole('Super Admin');

        // Kepala Sekolah
        $userKepsek = User::firstOrCreate(
            ['email' => 'kepsek@sekolah.com'],
            ['name' => 'Bpk/Ibu Kepala Sekolah', 'password' => Hash::make('password')]
        );
        if (!$userKepsek->hasRole('Kepala Sekolah')) $userKepsek->assignRole('Kepala Sekolah');

        // Guru
        $userGuru = User::firstOrCreate(
            ['email' => 'guru@sekolah.com'],
            ['name' => 'Budi Santoso, S.Pd', 'password' => Hash::make('password')]
        );
        if (!$userGuru->hasRole('Guru')) $userGuru->assignRole('Guru');

        // Admin Konten — akun baru
        $userAdminKonten = User::firstOrCreate(
            ['email' => 'konten@sekolah.com'],
            ['name' => 'Admin Konten Website', 'password' => Hash::make('password')]
        );
        if (!$userAdminKonten->hasRole('Admin Konten')) $userAdminKonten->assignRole('Admin Konten');

        // Pastikan akun guru memiliki profil di tabel Guru
        $guruProfile = Guru::firstOrCreate(
            ['user_id' => $userGuru->id],
            [
                'nip'           => '198001012010011001',
                'nama'          => 'Budi Santoso, S.Pd',
                'jenis_kelamin' => 'L',
                'no_telepon'    => '081234567890',
            ]
        );

        $kelas = Kelas::first();
        if ($kelas) {
            $kelas->update(['wali_kelas_id' => $guruProfile->id]);
        }
    }
}
