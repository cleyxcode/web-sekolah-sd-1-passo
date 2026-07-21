<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\KelasTingkatPage;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Services\KelasSidebarAccess;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KelasTingkatPageTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Super Admin', 'Kepala Sekolah', 'Guru', 'Admin Konten'] as $role) {
            Role::findOrCreate($role);
        }
    }

    public function test_super_admin_bisa_membuka_semua_halaman_kelas_1_sampai_6(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Super Admin');

        $this->actingAs($user);

        foreach (KelasSidebarAccess::TINGKAT_SEMUA as $tingkat) {
            $this->get('/admin/kelas-tingkat/'.$tingkat)->assertOk();
        }
    }

    public function test_guru_hanya_bisa_membuka_tingkat_yang_diwalikan(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Guru');

        $guru = Guru::query()->create([
            'user_id' => $user->id,
            'nip' => '198801012015011099',
            'nama' => 'Guru Feature Test',
            'jenis_kelamin' => 'L',
        ]);

        $tahunAjaran = TahunAjaran::query()->first()
            ?? TahunAjaran::query()->create([
                'nama' => '2025/2026-FT',
                'semester' => '1',
                'is_active' => true,
            ]);

        Kelas::query()->create([
            'nama_kelas' => '6FT',
            'tingkat' => 6,
            'wali_kelas_id' => $guru->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $this->actingAs($user);

        $this->get('/admin/kelas-tingkat/6')->assertOk();
        $this->get('/admin/kelas-tingkat/1')->assertForbidden();
    }

    public function test_admin_konten_tidak_bisa_akses_pintasan_kelas(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Admin Konten');

        $this->actingAs($user);

        $this->get('/admin/kelas-tingkat/1')->assertForbidden();
    }

    public function test_navigasi_super_admin_memiliki_enam_item_kelas(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Super Admin');

        $this->actingAs($user);

        $items = KelasTingkatPage::getNavigationItems();

        $this->assertCount(6, $items);
        $this->assertSame(
            ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6'],
            array_map(fn ($item) => $item->getLabel(), $items)
        );
    }

    public function test_navigasi_guru_hanya_tingkat_perwalian(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Guru');

        $guru = Guru::query()->create([
            'user_id' => $user->id,
            'nip' => '198801012015011098',
            'nama' => 'Guru Nav Test',
            'jenis_kelamin' => 'P',
        ]);

        $tahunAjaran = TahunAjaran::query()->first()
            ?? TahunAjaran::query()->create([
                'nama' => '2025/2026-NAV',
                'semester' => '1',
                'is_active' => true,
            ]);

        Kelas::query()->create([
            'nama_kelas' => '2NAV',
            'tingkat' => 2,
            'wali_kelas_id' => $guru->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $this->actingAs($user);

        $items = KelasTingkatPage::getNavigationItems();

        $this->assertCount(1, $items);
        $this->assertSame('Kelas 2', $items[0]->getLabel());
    }

    public function test_livewire_page_menampilkan_data_kelas(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Super Admin');

        $tahunAjaran = TahunAjaran::query()->first()
            ?? TahunAjaran::query()->create([
                'nama' => '2025/2026-LW',
                'semester' => '1',
                'is_active' => true,
            ]);

        Kelas::query()->create([
            'nama_kelas' => '1LW',
            'tingkat' => 1,
            'wali_kelas_id' => null,
            'tahun_ajaran_id' => $tahunAjaran->id,
        ]);

        $this->actingAs($user);

        Livewire::test(KelasTingkatPage::class, ['tingkat' => 1])
            ->assertOk()
            ->assertSee('1LW')
            ->assertSee('Rombongan Belajar Kelas 1');
    }
}
