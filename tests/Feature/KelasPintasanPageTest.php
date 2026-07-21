<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\KelasPintasanPage;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KelasPintasanPageTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Super Admin', 'Kepala Sekolah', 'Guru', 'Admin Konten'] as $role) {
            Role::findOrCreate($role);
        }
    }

    public function test_super_admin_bisa_membuka_halaman_rombongan_belajar(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Super Admin');

        $kelas = Kelas::query()->first();
        $this->assertNotNull($kelas);

        $this->actingAs($user)
            ->get('/admin/kelas-pintasan/'.$kelas->id)
            ->assertOk();
    }

    public function test_guru_hanya_bisa_membuka_kelas_yang_diwalikan(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Guru');

        $guru = Guru::query()->create([
            'user_id' => $user->id,
            'nip' => '198801012015011099',
            'nama' => 'Guru Feature Test',
            'jenis_kelamin' => 'L',
        ]);

        $tahunAjaran = $this->tahunAjaran('2025/2026-FT');

        $anya = Kelas::query()->create([
            'nama_kelas' => 'Anya',
            'tingkat' => 6,
            'wali_kelas_id' => $guru->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $lain = Kelas::query()->create([
            'nama_kelas' => '6Lain',
            'tingkat' => 6,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $this->actingAs($user);

        $this->get('/admin/kelas-pintasan/'.$anya->id)->assertOk();
        $this->get('/admin/kelas-pintasan/'.$lain->id)->assertForbidden();
    }

    public function test_admin_konten_tidak_bisa_akses_pintasan_kelas(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Admin Konten');

        $kelas = Kelas::query()->first();
        $this->assertNotNull($kelas);

        $this->actingAs($user)
            ->get('/admin/kelas-pintasan/'.$kelas->id)
            ->assertForbidden();
    }

    public function test_navigasi_super_admin_menampilkan_nama_kelas_asli(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Super Admin');

        $tahunAjaran = $this->tahunAjaran('2025/2026-NAV');

        Kelas::query()->create([
            'nama_kelas' => 'Anya',
            'tingkat' => 3,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $this->actingAs($user);

        $labels = array_map(
            fn ($item) => $item->getLabel(),
            KelasPintasanPage::getNavigationItems()
        );

        $this->assertContains('Anya', $labels);
    }

    public function test_navigasi_guru_hanya_kelas_perwalian(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Guru');

        $guru = Guru::query()->create([
            'user_id' => $user->id,
            'nip' => '198801012015011098',
            'nama' => 'Guru Nav Test',
            'jenis_kelamin' => 'P',
        ]);

        $tahunAjaran = $this->tahunAjaran('2025/2026-NAV2');

        Kelas::query()->create([
            'nama_kelas' => 'Mawar',
            'tingkat' => 2,
            'wali_kelas_id' => $guru->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        Kelas::query()->create([
            'nama_kelas' => 'Melati',
            'tingkat' => 2,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $this->actingAs($user);

        $items = KelasPintasanPage::getNavigationItems();
        $labels = array_map(fn ($item) => $item->getLabel(), $items);

        $this->assertCount(1, $items);
        $this->assertSame(['Mawar'], $labels);
    }

    public function test_livewire_page_menampilkan_nama_kelas_custom(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Super Admin');

        $tahunAjaran = $this->tahunAjaran('2025/2026-LW');

        $anya = Kelas::query()->create([
            'nama_kelas' => 'Anya',
            'tingkat' => 1,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $this->actingAs($user);

        Livewire::test(KelasPintasanPage::class, ['kelasId' => $anya->id])
            ->assertOk()
            ->assertSee('Anya')
            ->assertSee('Daftar Siswa Aktif');
    }

    private function tahunAjaran(string $nama): TahunAjaran
    {
        return TahunAjaran::query()->firstOrCreate(
            ['nama' => $nama],
            ['semester' => '1', 'is_active' => true]
        );
    }
}
