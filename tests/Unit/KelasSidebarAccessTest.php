<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Services\KelasSidebarAccess;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KelasSidebarAccessTest extends TestCase
{
    use DatabaseTransactions;

    private KelasSidebarAccess $access;

    protected function setUp(): void
    {
        parent::setUp();

        $this->access = new KelasSidebarAccess;

        foreach (['Super Admin', 'Kepala Sekolah', 'Guru', 'Admin Konten'] as $role) {
            Role::findOrCreate($role);
        }
    }

    public function test_super_admin_melihat_semua_rombongan_belajar(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->assertTrue($this->access->canAccessSidebar($user));
        $this->assertGreaterThanOrEqual(1, $this->access->accessibleKelas($user)->count());
    }

    public function test_kepala_sekolah_melihat_semua_rombongan_belajar(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Kepala Sekolah');

        $this->assertTrue($this->access->canAccessSidebar($user));
    }

    public function test_guru_hanya_melihat_kelas_yang_diwalikan(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Guru');

        $guru = Guru::query()->create([
            'user_id' => $user->id,
            'nip' => '199901012020011001',
            'nama' => 'Guru Unit Test',
            'jenis_kelamin' => 'L',
        ]);

        $tahunAjaran = $this->tahunAjaran();

        $anya = Kelas::query()->create([
            'nama_kelas' => 'Anya',
            'tingkat' => 4,
            'wali_kelas_id' => $guru->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        Kelas::query()->create([
            'nama_kelas' => '4Z',
            'tingkat' => 4,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $list = $this->access->accessibleKelas($user);

        $this->assertCount(1, $list);
        $this->assertTrue($list->first()->is($anya));
        $this->assertTrue($this->access->canAccessKelas($user, $anya));
        $this->assertFalse($this->access->canAccessKelas($user, Kelas::query()->where('nama_kelas', '4Z')->first()));
    }

    public function test_guru_tanpa_kelas_perwalian_tidak_punya_akses_sidebar(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Guru');

        Guru::query()->create([
            'user_id' => $user->id,
            'nip' => '199901012020011002',
            'nama' => 'Guru Tanpa Kelas',
            'jenis_kelamin' => 'P',
        ]);

        $this->assertTrue($this->access->accessibleKelas($user)->isEmpty());
        $this->assertFalse($this->access->canAccessSidebar($user));
    }

    public function test_admin_konten_tidak_punya_akses_pintasan_kelas(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Admin Konten');

        $this->assertFalse($this->access->canAccessSidebar($user));
        $this->assertSame(0, $this->access->accessibleKelas($user)->count());
    }

    public function test_resolve_kelas_mengembalikan_counter_lengkap(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $tahunAjaran = $this->tahunAjaran('2025/2026-UT');

        $kelas = Kelas::query()->create([
            'nama_kelas' => '2X',
            'tingkat' => 2,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $resolved = $this->access->resolveKelas($user, $kelas);

        $this->assertNotNull($resolved);
        $this->assertSame('2X', $resolved->nama_kelas);
        $this->assertNotNull($resolved->siswas_count);
    }

    private function tahunAjaran(string $nama = '2025/2026'): TahunAjaran
    {
        return TahunAjaran::query()->first()
            ?? TahunAjaran::query()->create([
                'nama' => $nama,
                'semester' => '1',
                'is_active' => true,
            ]);
    }
}
