<?php

namespace App\Providers;

use App\Models\Berita;
use App\Models\CatatanPerkembangan;
use App\Models\Galeri;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\KalenderAkademik;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\OrangTua;
use App\Models\Pendaftaran;
use App\Models\Presensi;
use App\Models\ProfilSekolah;
use App\Models\RiwayatKelas;
use App\Models\SettingSekolah;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Policies\BeritaPolicy;
use App\Policies\CatatanPerkembanganPolicy;
use App\Policies\GaleriPolicy;
use App\Policies\GuruPolicy;
use App\Policies\JadwalPelajaranPolicy;
use App\Policies\KalenderAkademikPolicy;
use App\Policies\KelasPolicy;
use App\Policies\MataPelajaranPolicy;
use App\Policies\NilaiPolicy;
use App\Policies\OrangTuaPolicy;
use App\Policies\PendaftaranPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PresensiPolicy;
use App\Policies\ProfilSekolahPolicy;
use App\Policies\RiwayatKelasPolicy;
use App\Policies\RolePolicy;
use App\Policies\SettingSekolahPolicy;
use App\Policies\SiswaPolicy;
use App\Policies\TahunAjaranPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin() || $user->hasRole('Super Admin')) {
                return true;
            }

            return null;
        });

        // Daftarkan Role/Permission Policy (untuk plugin Spatie)
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);

        // Daftarkan semua Policy model aplikasi
        Gate::policy(Berita::class, BeritaPolicy::class);
        Gate::policy(Galeri::class, GaleriPolicy::class);
        Gate::policy(Guru::class, GuruPolicy::class);
        Gate::policy(OrangTua::class, OrangTuaPolicy::class);
        Gate::policy(Siswa::class, SiswaPolicy::class);
        Gate::policy(Kelas::class, KelasPolicy::class);
        Gate::policy(MataPelajaran::class, MataPelajaranPolicy::class);
        Gate::policy(JadwalPelajaran::class, JadwalPelajaranPolicy::class);
        Gate::policy(Presensi::class, PresensiPolicy::class);
        Gate::policy(Nilai::class, NilaiPolicy::class);
        Gate::policy(KalenderAkademik::class, KalenderAkademikPolicy::class);
        Gate::policy(TahunAjaran::class, TahunAjaranPolicy::class);
        Gate::policy(ProfilSekolah::class, ProfilSekolahPolicy::class);
        Gate::policy(SettingSekolah::class, SettingSekolahPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Pendaftaran::class, PendaftaranPolicy::class);
        Gate::policy(CatatanPerkembangan::class, CatatanPerkembanganPolicy::class);
        Gate::policy(RiwayatKelas::class, RiwayatKelasPolicy::class);

        // Gate khusus: Proses Naik Kelas Otomatis
        // Dapat dipanggil via: Gate::allows('naik-kelas') atau $user->can('naik-kelas')
        // Super Admin bypass otomatis via Gate::before di atas.
        Gate::define('naik-kelas', function (User $user) {
            return $user->checkPermissionTo('naik-kelas');
        });

        if (Schema::hasTable('setting_sekolahs')) {
            $settings = SettingSekolah::first();
            View::share('settings', $settings);
        }
    }
}
