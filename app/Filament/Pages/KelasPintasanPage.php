<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Resources\Nilais\NilaiResource;
use App\Filament\Resources\Presensis\PresensiResource;
use App\Filament\Resources\Siswas\SiswaResource;
use App\Filament\Resources\Tugas\TugasResource;
use App\Models\CatatanPerkembangan;
use App\Models\JadwalPelajaran;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use function Filament\Support\original_request;

/**
 * Pintasan sidebar per rombongan belajar (nama_kelas: 1A, Anya, dll).
 */
class KelasPintasanPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Pintasan Kelas';

    protected static ?string $slug = 'kelas-pintasan/{kelasId}';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.kelas-pintasan';

    public ?Kelas $kelas = null;

    public string $activeTab = 'siswa';

    public function mount(int $kelasId): void
    {
        $user = Auth::user();
        abort_unless($user !== null, 403);

        $access = app(KelasSidebarAccess::class);
        $record = $access->resolveKelas($user, $kelasId);

        abort_unless($record !== null, 403);

        $this->kelas = $record;
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

    public function getTitle(): string|Htmlable
    {
        return $this->kelas?->nama_kelas ?? 'Pintasan Kelas';
    }

    public function getHeading(): string|Htmlable
    {
        return $this->kelas?->nama_kelas ?? 'Pintasan Kelas';
    }

    public function getSubheading(): string|Htmlable|null
    {
        if ($this->kelas === null) {
            return null;
        }

        $tingkat = $this->kelas->tingkat;
        $wali = $this->kelas->waliKelas?->nama ?? 'Belum ditentukan';
        $tahun = $this->kelas->tahunAjaran?->nama ?? '—';

        return "Tingkat {$tingkat} · Wali: {$wali} · TA {$tahun}";
    }

    public function setActiveTab(string $tab): void
    {
        if (! in_array($tab, ['siswa', 'nilai', 'presensi', 'tugas', 'jadwal'], true)) {
            return;
        }

        $this->activeTab = $tab;
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return match ($this->activeTab) {
            'nilai' => $this->nilaiTable($table),
            'presensi' => $this->presensiTable($table),
            'tugas' => $this->tugasTable($table),
            'jadwal' => $this->jadwalTable($table),
            default => $this->siswaTable($table),
        };
    }

    protected function siswaTable(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Siswa::query()
                ->where('kelas_id', $this->kelas?->id)
                ->where('status', 'aktif')
                ->orderBy('nama'))
            ->heading('Daftar Siswa Aktif')
            ->description('Semua siswa yang terdaftar di rombongan belajar ini.')
            ->columns([
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state === 'P' ? 'Perempuan' : 'Laki-laki')
                    ->color(fn (?string $state): string => $state === 'P' ? 'danger' : 'info'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('success'),
            ])
            ->recordUrl(fn (Siswa $record): string => SiswaResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading('Belum ada siswa')
            ->emptyStateDescription('Tambahkan siswa ke rombongan belajar ini melalui menu Siswa.')
            ->emptyStateIcon(Heroicon::OutlinedUserGroup)
            ->paginated([10, 25, 50]);
    }

    protected function nilaiTable(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Nilai::query()
                ->with(['siswa', 'mataPelajaran'])
                ->where('kelas_id', $this->kelas?->id)
                ->latest())
            ->heading('Data Nilai')
            ->description('Nilai siswa di rombongan belajar ini.')
            ->columns([
                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mataPelajaran.nama')
                    ->label('Mapel')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('semester')
                    ->label('Sem')
                    ->badge(),
                TextColumn::make('jenis_ujian')
                    ->label('Ujian')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('nilai_angka')
                    ->label('Nilai')
                    ->numeric(decimalPlaces: 1)
                    ->sortable()
                    ->alignCenter()
                    ->color(fn ($state): string => match (true) {
                        $state >= 90 => 'success',
                        $state >= 75 => 'info',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
            ])
            ->recordUrl(fn (Nilai $record): string => NilaiResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading('Belum ada nilai')
            ->emptyStateIcon(Heroicon::OutlinedDocumentText)
            ->paginated([10, 25, 50]);
    }

    protected function presensiTable(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Presensi::query()
                ->with('siswa')
                ->where('kelas_id', $this->kelas?->id)
                ->latest('tanggal'))
            ->heading('Presensi')
            ->description('Catatan kehadiran siswa di kelas ini.')
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        default => 'danger',
                    }),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(40)
                    ->placeholder('—'),
            ])
            ->recordUrl(fn (Presensi $record): string => PresensiResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading('Belum ada presensi')
            ->emptyStateIcon(Heroicon::OutlinedClipboardDocumentCheck)
            ->paginated([10, 25, 50]);
    }

    protected function tugasTable(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Tugas::query()
                ->with('guru')
                ->where('kelas_id', $this->kelas?->id)
                ->latest())
            ->heading('Tugas')
            ->description('Daftar tugas yang diberikan untuk kelas ini.')
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),
                TextColumn::make('mata_pelajaran')
                    ->label('Mapel')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('guru.nama')
                    ->label('Guru')
                    ->placeholder('—'),
                TextColumn::make('deadline')
                    ->label('Deadline')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'selesai' => 'info',
                        default => 'gray',
                    }),
            ])
            ->recordUrl(fn (Tugas $record): string => TugasResource::getUrl('edit', ['record' => $record]))
            ->emptyStateHeading('Belum ada tugas')
            ->emptyStateIcon(Heroicon::OutlinedClipboardDocumentList)
            ->paginated([10, 25, 50]);
    }

    protected function jadwalTable(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => JadwalPelajaran::query()
                ->with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $this->kelas?->id)
                ->orderBy('hari')
                ->orderBy('jam_mulai'))
            ->heading('Jadwal Pelajaran')
            ->description('Jadwal mingguan rombongan belajar ini.')
            ->columns([
                TextColumn::make('hari')
                    ->label('Hari')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('jam_mulai')
                    ->label('Mulai')
                    ->time('H:i'),
                TextColumn::make('jam_selesai')
                    ->label('Selesai')
                    ->time('H:i'),
                TextColumn::make('mataPelajaran.nama')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                TextColumn::make('guru.nama')
                    ->label('Guru')
                    ->placeholder('—'),
            ])
            ->emptyStateHeading('Belum ada jadwal')
            ->emptyStateIcon(Heroicon::OutlinedCalendarDays)
            ->paginated([10, 25, 50]);
    }

    /**
     * @return array{siswa: int, nilai: int, presensi: int, tugas: int, jadwal: int, catatan: int}
     */
    public function getRingkasan(): array
    {
        if ($this->kelas === null) {
            return ['siswa' => 0, 'nilai' => 0, 'presensi' => 0, 'tugas' => 0, 'jadwal' => 0, 'catatan' => 0];
        }

        $siswaIds = Siswa::query()
            ->where('kelas_id', $this->kelas->id)
            ->where('status', 'aktif')
            ->pluck('id');

        return [
            'siswa' => $this->kelas->siswas_count ?? $siswaIds->count(),
            'nilai' => $this->kelas->nilais_count ?? 0,
            'presensi' => $this->kelas->presensis_count ?? 0,
            'tugas' => $this->kelas->tugas_count ?? 0,
            'jadwal' => $this->kelas->jadwal_pelajarans_count ?? 0,
            'catatan' => CatatanPerkembangan::query()->whereIn('siswa_id', $siswaIds)->count(),
        ];
    }

    /**
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

        foreach ($access->accessibleKelas($user)->values() as $index => $kelas) {
            $tingkat = (int) $kelas->tingkat;

            $items[] = NavigationItem::make($kelas->nama_kelas)
                ->group("Kelas {$tingkat}")
                ->icon(static::iconForTingkat($tingkat))
                ->isActiveWhen(fn (): bool => original_request()->routeIs(static::getRouteName())
                    && (int) original_request()->route('kelasId') === $kelas->id)
                ->sort(($tingkat * 100) + $index)
                ->badge((string) ($kelas->siswas_count ?? 0), color: 'info')
                ->badgeTooltip('Jumlah siswa aktif')
                ->url(static::getUrl(['kelasId' => $kelas->id]));
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

        Route::get('/kelas-pintasan/{kelasId}', static::class)
            ->whereNumber('kelasId')
            ->middleware($middleware)
            ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
            ->name(static::getRelativeRouteName($panel));
    }

    public static function getRelativeRouteName(Panel $panel): string
    {
        return 'kelas-pintasan';
    }

    public static function getRoutePath(Panel $panel): string
    {
        return '/kelas-pintasan/{kelasId}';
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
