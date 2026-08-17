<?php

namespace App\Filament\Resources\Nilais\Pages;

use App\Filament\Resources\Nilais\NilaiResource;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\SettingSekolah;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page as ResourcePage;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class RekapNilaiKelas extends ResourcePage implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = NilaiResource::class;

    protected string $view = 'filament.resources.nilais.pages.rekap-nilai-kelas';

    protected static ?string $title = 'Rekap Nilai Kelas';

    protected static ?string $navigationLabel = 'Rekap Nilai';

    public ?array $data = [];

    public ?int $kelas_id = null;

    public ?string $semester = null;

    public ?string $jenis_ujian = null;

    public ?int $tahun_ajaran_id = null;

    public function mount(): void
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        // Default: kelas wali kelas guru
        if ($guru) {
            $kelas = Kelas::where('wali_kelas_id', $guru->id)->first();
            $this->kelas_id = $kelas?->id;
        }

        $this->tahun_ajaran_id = TahunAjaran::orderByDesc('nama')->value('id');
        $this->semester = '1';
        $this->jenis_ujian = 'UTS';

        $this->form->fill([
            'kelas_id' => $this->kelas_id,
            'tahun_ajaran_id' => $this->tahun_ajaran_id,
            'semester' => $this->semester,
            'jenis_ujian' => $this->jenis_ujian,
        ]);
    }

    public function form(Schema $form): Schema
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $guru = Guru::where('user_id', $user->id)->first();

        $kelasQuery = $isSuperAdmin
            ? Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get()
            : Kelas::where('wali_kelas_id', $guru?->id)->orderBy('tingkat')->orderBy('nama_kelas')->get();

        $kelasOptions = $kelasQuery->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas}"]);

        return $form
            ->schema([
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->options($kelasOptions)
                    ->required()
                    ->searchable()
                    ->live(),

                Select::make('semester')
                    ->label('Semester')
                    ->options(['1' => 'Semester 1', '2' => 'Semester 2'])
                    ->required()
                    ->native(false)
                    ->live(),

                Select::make('jenis_ujian')
                    ->label('Jenis Ujian')
                    ->options(['UTS' => 'UTS', 'UAS' => 'UAS'])
                    ->required()
                    ->native(false)
                    ->live(),

                Select::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->options(fn () => TahunAjaran::orderByDesc('nama')->pluck('nama', 'id'))
                    ->required()
                    ->searchable()
                    ->live(),
            ])
            ->columns(4)
            ->statePath('data');
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('cetak_rekap')
                ->label('🖨️ Cetak / Export PDF')
                ->color('success')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('admin.cetak-rekap-kelas', [
                    'kelas_id' => $this->data['kelas_id'] ?? $this->kelas_id,
                    'semester' => $this->data['semester'] ?? $this->semester,
                    'jenis_ujian' => $this->data['jenis_ujian'] ?? $this->jenis_ujian,
                    'tahun_ajaran_id' => $this->data['tahun_ajaran_id'] ?? $this->tahun_ajaran_id,
                ]))
                ->openUrlInNewTab(),
        ];
    }

    public function getKelasData(): ?Kelas
    {
        $kelasId = $this->data['kelas_id'] ?? $this->kelas_id;

        return $kelasId ? Kelas::with('waliKelas')->find($kelasId) : null;
    }

    public function buildRekapContent(): View
    {
        $kelasId = $this->data['kelas_id'] ?? $this->kelas_id;
        $semester = $this->data['semester'] ?? $this->semester;
        $jenisUjian = $this->data['jenis_ujian'] ?? $this->jenis_ujian;
        $tahunAjaranId = $this->data['tahun_ajaran_id'] ?? $this->tahun_ajaran_id;

        $kelas = Kelas::with('waliKelas')->find($kelasId);
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);
        $sekolah = SettingSekolah::first();

        // Siswa aktif di kelas ini
        $siswas = Siswa::where('kelas_id', $kelasId)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        // Semua nilai untuk kelas, semester, jenis ujian, dan tahun ajaran
        $allNilais = Nilai::with('mataPelajaran')
            ->where('kelas_id', $kelasId)
            ->where('semester', $semester)
            ->where('jenis_ujian', $jenisUjian)
            ->when($tahunAjaranId, fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->get();

        // Group by siswa_id
        $nilaisGrouped = $allNilais->groupBy('siswa_id');

        // Mata pelajaran yang ada di data nilai ini
        $mataPelajaranIds = $allNilais->pluck('mata_pelajaran_id')->unique();
        $mataPelajarans = MataPelajaran::whereIn('id', $mataPelajaranIds)
            ->orderBy('nama')
            ->get();

        return view('rapor.rekap-kelas', compact(
            'kelas', 'semester', 'jenisUjian', 'tahunAjaran',
            'siswas', 'nilaisGrouped', 'mataPelajarans', 'sekolah'
        ));
    }

    public function getPreviewData(): array
    {
        $kelasId = $this->data['kelas_id'] ?? $this->kelas_id;
        $semester = $this->data['semester'] ?? $this->semester;
        $jenisUjian = $this->data['jenis_ujian'] ?? $this->jenis_ujian;
        $tahunAjaranId = $this->data['tahun_ajaran_id'] ?? $this->tahun_ajaran_id;

        $kelas = Kelas::with('waliKelas')->find($kelasId);
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);

        $siswas = Siswa::where('kelas_id', $kelasId)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $allNilais = Nilai::with(['mataPelajaran', 'siswa'])
            ->where('kelas_id', $kelasId)
            ->where('semester', $semester)
            ->where('jenis_ujian', $jenisUjian)
            ->when($tahunAjaranId, fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->get();

        $nilaisGrouped = $allNilais->groupBy('siswa_id');
        $mataPelajaranIds = $allNilais->pluck('mata_pelajaran_id')->unique();
        $mataPelajarans = MataPelajaran::whereIn('id', $mataPelajaranIds)->orderBy('nama')->get();

        return compact('kelas', 'semester', 'jenisUjian', 'tahunAjaran', 'siswas', 'nilaisGrouped', 'mataPelajarans');
    }
}
