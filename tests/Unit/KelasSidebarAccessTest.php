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

    public function test_super_admin_dapat_semua_tingkat_1_sampai_6(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $tingkat = $this->access->accessibleTingkats($user)->all();

        $this->assertSame([1, 2, 3, 4, 5, 6], $tingkat);
        $this->assertTrue($this->access->canAccessSidebar($user));
        $this->assertTrue($this->access->canAccessTingkat($user, 3));
        $this->assertFalse($this->access->canAccessTingkat($user, 7));
    }

    public function test_kepala_sekolah_dapat_semua_tingkat(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Kepala Sekolah');

        $this->assertSame([1, 2, 3, 4, 5, 6], $this->access->accessibleTingkats($user)->all());
    }

    public function test_guru_hanya_melihat_tingkat_kelas_yang_diwalikan(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Guru');

        $guru = Guru::query()->create([
            'user_id' => $user->id,
            'nip' => '199901012020011001',
            'nama' => 'Guru Unit Test',
            'jenis_kelamin' => 'L',
        ]);

        $tahunAjaran = TahunAjaran::query()->first()
            ?? TahunAjaran::query()->create([
                'nama' => '2025/2026',
                'semester' => '1',
                'is_active' => true,
            ]);

        Kelas::query()->create([
            'nama_kelas' => '4U',
            'tingkat' => 4,
            'wali_kelas_id' => $guru->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        Kelas::query()->create([
            'nama_kelas' => '5U',
            'tingkat' => 5,
            'wali_kelas_id' => $guru->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $tingkat = $this->access->accessibleTingkats($user)->all();

        $this->assertSame([4, 5], $tingkat);
        $this->assertTrue($this->access->canAccessTingkat($user, 4));
        $this->assertFalse($this->access->canAccessTingkat($user, 1));
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

        $this->assertTrue($this->access->accessibleTingkats($user)->isEmpty());
        $this->assertFalse($this->access->canAccessSidebar($user));
    }

    public function test_admin_konten_tidak_punya_akses_pintasan_kelas(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Admin Konten');

        $this->assertFalse($this->access->canAccessSidebar($user));
        $this->assertSame([], $this->access->accessibleTingkats($user)->all());
    }

    public function test_kelas_for_tingkat_super_admin_melihat_semua_rombongan(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $tahunAjaran = TahunAjaran::query()->first()
            ?? TahunAjaran::query()->create([
                'nama' => '2025/2026-UT',
                'semester' => '1',
                'is_active' => true,
            ]);

        Kelas::query()->create([
            'nama_kelas' => '2X',
            'tingkat' => 2,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $list = $this->access->kelasForTingkat($user, 2);

        $this->assertTrue($list->contains(fn (Kelas $k): bool => $k->nama_kelas === '2X'));
    }

    public function test_kelas_for_tingkat_guru_hanya_kelas_sendiri(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Guru');

        $guru = Guru::query()->create([
            'user_id' => $user->id,
            'nip' => '199901012020011003',
            'nama' => 'Guru Filter Kelas',
            'jenis_kelamin' => 'L',
        ]);

        $tahunAjaran = TahunAjaran::query()->first()
            ?? TahunAjaran::query()->create([
                'nama' => '2025/2026-UT2',
                'semester' => '1',
                'is_active' => true,
            ]);

        $milikGuru = Kelas::query()->create([
            'nama_kelas' => '3G',
            'tingkat' => 3,
            'wali_kelas_id' => $guru->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        Kelas::query()->create([
            'nama_kelas' => '3Z',
            'tingkat' => 3,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $list = $this->access->kelasForTingkat($user, 3);

        $this->assertCount(1, $list);
        $this->assertTrue($list->first()->is($milikGuru));
    }
}
