<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\SettingSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Kelas;

class RaporController extends Controller
{
    /**
     * Cetak E-Rapor dari panel Admin/Guru.
     * Hanya bisa diakses oleh user yang sudah login (Filament auth).
     * Guru/Wali Kelas hanya bisa cetak rapor siswanya sendiri.
     */
    public function cetakAdmin(Nilai $nilai)
    {
        $user = Auth::user();

        // Pastikan user sudah login
        if (!$user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        // Kepala Sekolah & Super Admin bisa cetak semua
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isKepsek     = $user->hasRole('Kepala Sekolah');

        if (!$isSuperAdmin && !$isKepsek) {
            // Wali Kelas: hanya bisa cetak rapor siswa di kelasnya
            $guru = Guru::where('user_id', $user->id)->first();
            if (!$guru) {
                abort(403, 'Anda tidak memiliki akses untuk mencetak rapor ini.');
            }

            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
            if (!$kelasIds->contains($nilai->kelas_id)) {
                abort(403, 'Anda hanya bisa mencetak rapor siswa di kelas yang Anda wali.');
            }
        }

        // Ambil semua nilai siswa untuk semester & jenis ujian yang sama
        $siswa       = $nilai->siswa;
        $kelas       = $nilai->kelas;
        $semester    = $nilai->semester;
        $jenisUjian  = $nilai->jenis_ujian;
        $tahunAjaran = $nilai->tahunAjaran;

        $nilais = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('kelas_id', $kelas?->id)
            ->where('semester', $semester)
            ->where('jenis_ujian', $jenisUjian)
            ->where('tahun_ajaran_id', $tahunAjaran?->id)
            ->get();

        // Load relasi wali kelas
        $kelas?->load('waliKelas');

        $sekolah = SettingSekolah::first();

        return view('rapor.cetak-rapor', compact(
            'siswa', 'kelas', 'semester', 'jenisUjian', 'tahunAjaran', 'nilais', 'sekolah'
        ));
    }
}
