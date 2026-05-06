<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\SettingSekolah;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapKelasController extends Controller
{
    /**
     * Tampilkan halaman cetak rekap nilai kelas.
     * Diakses via query string: ?kelas_id=&semester=&jenis_ujian=&tahun_ajaran_id=
     */
    public function cetak(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $kelasId       = $request->query('kelas_id');
        $semester      = $request->query('semester');
        $jenisUjian    = $request->query('jenis_ujian');
        $tahunAjaranId = $request->query('tahun_ajaran_id');

        // Otorisasi: Guru hanya bisa cetak rekap kelasnya sendiri
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isKepsek     = $user->hasRole('Kepala Sekolah');

        if (!$isSuperAdmin && !$isKepsek) {
            $guru = Guru::where('user_id', $user->id)->first();
            if (!$guru) {
                abort(403, 'Anda tidak memiliki akses.');
            }
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
            if (!$kelasIds->contains($kelasId)) {
                abort(403, 'Anda hanya bisa mencetak rekap nilai kelas yang Anda wali.');
            }
        }

        $kelas       = Kelas::with('waliKelas')->findOrFail($kelasId);
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);
        $sekolah     = SettingSekolah::first();

        $siswas = Siswa::where('kelas_id', $kelasId)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $allNilais = Nilai::with('mataPelajaran')
            ->where('kelas_id', $kelasId)
            ->where('semester', $semester)
            ->where('jenis_ujian', $jenisUjian)
            ->when($tahunAjaranId, fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->get();

        $nilaisGrouped    = $allNilais->groupBy('siswa_id');
        $mataPelajaranIds = $allNilais->pluck('mata_pelajaran_id')->unique();
        $mataPelajarans   = MataPelajaran::whereIn('id', $mataPelajaranIds)
            ->orderBy('nama')
            ->get();

        return view('rapor.rekap-kelas', compact(
            'kelas', 'semester', 'jenisUjian', 'tahunAjaran',
            'siswas', 'nilaisGrouped', 'mataPelajarans', 'sekolah'
        ));
    }
}
