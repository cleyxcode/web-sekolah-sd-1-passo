<?php

namespace App\Filament\Resources\Nilais\Pages;

use App\Filament\Resources\Nilais\NilaiResource;
use App\Models\Nilai;
use App\Models\SettingSekolah;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;
use Torgodly\Html2Media\Actions\Html2MediaAction;

class ViewNilai extends ViewRecord
{
    protected static string $resource = NilaiResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            EditAction::make(),
        ];

        if (class_exists(Html2MediaAction::class)) {
            $actions[] = Html2MediaAction::make('cetak_rapor')
                ->label('🖨️ Cetak E-Rapor')
                ->color('success')
                ->print()
                ->preview()
                ->savePdf()
                ->orientation('portrait')
                ->format('a4')
                ->filename(fn () => 'E-Rapor_'.str($this->record->siswa?->nama ?? 'siswa')->slug()
                    .'_Smt'.$this->record->semester
                    .'_'.$this->record->jenis_ujian
                )
                ->content(fn () => $this->buildRaporContent());
        } else {
            $actions[] = Action::make('cetak_rapor')
                ->label('🖨️ Cetak E-Rapor')
                ->color('success')
                ->url(fn () => route('admin.cetak-rapor', ['nilai' => $this->record->id]))
                ->openUrlInNewTab();
        }

        return $actions;
    }

    private function buildRaporContent(): View
    {
        $record = $this->record;
        $siswa = $record->siswa;
        $kelas = $record->kelas;
        $semester = $record->semester;
        $jenisUjian = $record->jenis_ujian;
        $tahunAjaran = $record->tahunAjaran;

        // Load semua nilai siswa untuk semester & jenis ujian ini
        $nilais = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa?->id)
            ->where('kelas_id', $kelas?->id)
            ->where('semester', $semester)
            ->where('jenis_ujian', $jenisUjian)
            ->where('tahun_ajaran_id', $tahunAjaran?->id)
            ->get();

        $kelas?->load('waliKelas');
        $sekolah = SettingSekolah::first();

        return view('rapor.cetak-rapor', compact(
            'siswa', 'kelas', 'semester', 'jenisUjian', 'tahunAjaran', 'nilais', 'sekolah'
        ));
    }
}
