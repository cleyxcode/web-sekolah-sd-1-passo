<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\CatatanPerkembangan;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Services\KelasSidebarAccess;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Pages\PageConfiguration;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use function Filament\Support\original_request;

/**
 * Pintasan sidebar per tingkat kelas (1–6).
 * Menampilkan ringkasan seluruh data akademik kelas pada tingkat tersebut.
 */
class KelasTingkatPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Pintasan Kelas';

    protected static ?string $slug = 'kelas-tingkat/{tingkat}';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.kelas-tingkat';

    public int $tingkat = 1;

    /** @var Collection<int, Kelas> */
    public Collection $kelasList;

    public function mount(int $tingkat): void
    {
        $user = Auth::user();
        abort_unless($user !== null, 403);

        $access = app(KelasSidebarAccess::class);
        abort_unless($access->canAccessTingkat($user, $tingkat), 403);

        $this->tingkat = $tingkat;
        $this->kelasList = $access->kelasForTingkat($user, $tingkat);
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        return app(KelasSidebarAccess::class)->canAccessSidebar($user);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function getNavigationLabel(): string
    {
        return 'Pintasan Kelas';
    }

    public function getTitle(): string|Htmlable
    {
        return "Data Kelas {$this->tingkat}";
    }

    public function getHeading(): string|Htmlable
    {
        return "Kelas {$this->tingkat}";
    }

    public function getSubheading(): string|Htmlable|null
    {
        $jumlah = $this->kelasList->count();

        return $jumlah > 0
            ? "{$jumlah} rombongan belajar pada tingkat {$this->tingkat}"
            : "Belum ada data kelas untuk tingkat {$this->tingkat}";
    }

    /**
     * Daftarkan satu item navigasi per tingkat yang boleh diakses user.
     *
     * @return array<NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        $user = Auth::user();

        if ($user === null) {
            return [];
        }

        $access = app(KelasSidebarAccess::class);
        $items = [];

        foreach ($access->accessibleTingkats($user) as $tingkat) {
            $items[] = NavigationItem::make("Kelas {$tingkat}")
                ->group(static::getNavigationGroup())
                ->icon(static::iconForTingkat($tingkat))
                ->isActiveWhen(fn (): bool => original_request()->routeIs(static::getRouteName())
                    && (int) original_request()->route('tingkat') === $tingkat)
                ->sort(static::getNavigationSort() + $tingkat)
                ->url(static::getUrl(['tingkat' => $tingkat]));
        }

        return $items;
    }

    public static function routes(Panel $panel, ?PageConfiguration $configuration = null): void
    {
        $middleware = static::getRouteMiddleware($panel);

        if ($configuration) {
            $middleware = [
                ...$middleware,
                "page-configuration:{$configuration->getKey()}",
            ];
        }

        Route::get('/kelas-tingkat/{tingkat}', static::class)
            ->whereNumber('tingkat')
            ->middleware($middleware)
            ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
            ->name(static::getRelativeRouteName($panel));
    }

    public static function getRelativeRouteName(Panel $panel): string
    {
        return 'kelas-tingkat';
    }

    public static function getRoutePath(Panel $panel): string
    {
        return '/kelas-tingkat/{tingkat}';
    }

    /**
     * Ringkasan angka untuk seluruh kelas di tingkat ini.
     *
     * @return array{siswa: int, nilai: int, presensi: int, tugas: int, jadwal: int, catatan: int}
     */
    public function getRingkasan(): array
    {
        $kelasIds = $this->kelasList->pluck('id');

        if ($kelasIds->isEmpty()) {
            return [
                'siswa' => 0,
                'nilai' => 0,
                'presensi' => 0,
                'tugas' => 0,
                'jadwal' => 0,
                'catatan' => 0,
            ];
        }

        $siswaIds = Siswa::query()
            ->whereIn('kelas_id', $kelasIds)
            ->where('status', 'aktif')
            ->pluck('id');

        return [
            'siswa' => $siswaIds->count(),
            'nilai' => Nilai::query()->whereIn('kelas_id', $kelasIds)->count(),
            'presensi' => Presensi::query()->whereIn('kelas_id', $kelasIds)->count(),
            'tugas' => Tugas::query()->whereIn('kelas_id', $kelasIds)->count(),
            'jadwal' => $this->kelasList->sum('jadwal_pelajarans_count'),
            'catatan' => CatatanPerkembangan::query()->whereIn('siswa_id', $siswaIds)->count(),
        ];
    }

    /**
     * Siswa aktif terbaru di tingkat ini (maks. 20).
     *
     * @return Collection<int, Siswa>
     */
    public function getSiswaTerbaru(): Collection
    {
        $kelasIds = $this->kelasList->pluck('id');

        if ($kelasIds->isEmpty()) {
            return collect();
        }

        return Siswa::query()
            ->with('kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->limit(20)
            ->get();
    }

    /**
     * Tugas terbaru di tingkat ini.
     *
     * @return Collection<int, Tugas>
     */
    public function getTugasTerbaru(): Collection
    {
        $kelasIds = $this->kelasList->pluck('id');

        if ($kelasIds->isEmpty()) {
            return collect();
        }

        return Tugas::query()
            ->with(['kelas', 'guru'])
            ->whereIn('kelas_id', $kelasIds)
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Presensi terbaru di tingkat ini.
     *
     * @return Collection<int, Presensi>
     */
    public function getPresensiTerbaru(): Collection
    {
        $kelasIds = $this->kelasList->pluck('id');

        if ($kelasIds->isEmpty()) {
            return collect();
        }

        return Presensi::query()
            ->with(['siswa', 'kelas'])
            ->whereIn('kelas_id', $kelasIds)
            ->latest('tanggal')
            ->limit(10)
            ->get();
    }

    public static function iconForTingkat(int $tingkat): Heroicon
    {
        return match ($tingkat) {
            1 => Heroicon::OutlinedBuildingLibrary,
            2 => Heroicon::OutlinedAcademicCap,
            3 => Heroicon::OutlinedBookOpen,
            4 => Heroicon::OutlinedUserGroup,
            5 => Heroicon::OutlinedRectangleStack,
            6 => Heroicon::OutlinedHomeModern,
            default => Heroicon::OutlinedHashtag,
        };
    }
}
