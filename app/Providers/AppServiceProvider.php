<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Super Admin bypass — dikerjakan paling awal sebelum Policy lain
        Gate::before(function (\App\Models\User $user, string $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        // Daftarkan Role/Permission Policy (untuk plugin Spatie)
        Gate::policy(\Spatie\Permission\Models\Role::class, \App\Policies\RolePolicy::class);
        Gate::policy(\Spatie\Permission\Models\Permission::class, \App\Policies\PermissionPolicy::class);

        // Daftarkan semua Policy model aplikasi
        Gate::policy(\App\Models\Berita::class, \App\Policies\BeritaPolicy::class);
        Gate::policy(\App\Models\Galeri::class, \App\Policies\GaleriPolicy::class);
        Gate::policy(\App\Models\Guru::class, \App\Policies\GuruPolicy::class);
        Gate::policy(\App\Models\OrangTua::class, \App\Policies\OrangTuaPolicy::class);
        Gate::policy(\App\Models\Siswa::class, \App\Policies\SiswaPolicy::class);
        Gate::policy(\App\Models\Kelas::class, \App\Policies\KelasPolicy::class);
        Gate::policy(\App\Models\MataPelajaran::class, \App\Policies\MataPelajaranPolicy::class);
        Gate::policy(\App\Models\JadwalPelajaran::class, \App\Policies\JadwalPelajaranPolicy::class);
        Gate::policy(\App\Models\Presensi::class, \App\Policies\PresensiPolicy::class);
        Gate::policy(\App\Models\Nilai::class, \App\Policies\NilaiPolicy::class);
        Gate::policy(\App\Models\Rapor::class, \App\Policies\RaporPolicy::class);
        Gate::policy(\App\Models\KalenderAkademik::class, \App\Policies\KalenderAkademikPolicy::class);
        Gate::policy(\App\Models\TahunAjaran::class, \App\Policies\TahunAjaranPolicy::class);
        Gate::policy(\App\Models\ProfilSekolah::class, \App\Policies\ProfilSekolahPolicy::class);
        Gate::policy(\App\Models\SettingSekolah::class, \App\Policies\SettingSekolahPolicy::class);
        Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        Gate::policy(\App\Models\Pendaftaran::class, \App\Policies\PendaftaranPolicy::class);
    }
}
