<?php

// Namespace letak folder untuk Controller ini
namespace App\Http\Controllers;

use App\Models\Nilai;            // Untuk mengambil data nilai siswa
use App\Models\SettingSekolah;   // Untuk mengambil info profil sekolah
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Mengecek siapa yang sedang login
use App\Models\Guru;             // Untuk mengambil profil guru (termasuk wali kelas)
use App\Models\Kelas;            // Untuk informasi kelas

/**
 * RaporController
 * 
 * Mengatur logika untuk mencetak Rapor Siswa secara perorangan (PDF/Print).
 * File ini digunakan oleh pihak internal sekolah (Admin dan Guru).
 */
class RaporController extends Controller
{
    /**
     * Mencetak E-Rapor dari panel Admin/Guru.
     * Hanya bisa diakses oleh admin/guru yang sudah login.
     * Terdapat sistem keamanan untuk mencegah guru dari kelas lain mencetak rapor kelas yang bukan miliknya.
     * 
     * @param Nilai $nilai (Ini otomatis mencari satu baris nilai yang di-klik user)
     */
    public function cetakAdmin(Nilai $nilai)
    {
        // 1. Ambil data pengguna yang sedang melakukan request (klik cetak)
        $user = Auth::user();

        // Jika yang klik ternyata tidak login, tolak akses (Error 403 Forbidden)
        if (!$user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        // 2. Sistem Hak Akses (Otorisasi)
        // Cek apakah yang login adalah orang berjabatan tinggi (Boleh cetak rapor apa saja)
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isKepsek     = $user->hasRole('Kepala Sekolah');

        // Jika BUKAN Super Admin dan BUKAN Kepala Sekolah (berarti dia Guru/Staff biasa)
        if (!$isSuperAdmin && !$isKepsek) {
            // Cari data Guru yang nyambung dengan akun login (User) ini
            $guru = Guru::where('user_id', $user->id)->first();
            
            // Kalau data gurunya tidak ada, berarti akun bermasalah/bukan guru
            if (!$guru) {
                abort(403, 'Anda tidak memiliki akses untuk mencetak rapor ini.');
            }

            // Cari ID kelas apa saja yang mana guru ini bertugas sebagai wali kelas
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
            
            // Cek apakah nilai siswa ini berasal dari kelas perwaliannya?
            // Jika kelas_id dari $nilai tidak ada di dalam $kelasIds milik si guru, tolak
            if (!$kelasIds->contains($nilai->kelas_id)) {
                abort(403, 'Anda hanya bisa mencetak rapor siswa di kelas yang Anda wali.');
            }
        }

        // 3. Persiapan Data untuk Tampilan Rapor (PDF)
        
        // Ambil data siswa dari nilai yang diklik
        $siswa       = $nilai->siswa;
        // Ambil kelas siswa
        $kelas       = $nilai->kelas;
        // Ambil jenis semester (Ganjil/Genap)
        $semester    = $nilai->semester;
        // Ambil jenis ujian (misal: UTS atau UAS)
        $jenisUjian  = $nilai->jenis_ujian;
        // Ambil tahun ajaran
        $tahunAjaran = $nilai->tahunAjaran;

        // 4. Proses pengambilan SEMUA nilai milik si siswa ini, 
        // tapi hanya nilai pada semester, ujian, kelas, dan tahun yang sama!
        // Ini memastikan semua mata pelajaran muncul di rapor
        $nilais = Nilai::with('mataPelajaran') // Sertakan detail mata pelajaran sekalian
            ->where('siswa_id', $siswa->id)
            ->where('kelas_id', $kelas?->id)
            ->where('semester', $semester)
            ->where('jenis_ujian', $jenisUjian)
            ->where('tahun_ajaran_id', $tahunAjaran?->id)
            ->get();

        // 5. Muat (Load) data wali kelas agar bisa dimunculkan namanya untuk tanda tangan rapor
        $kelas?->load('waliKelas');

        // 6. Ambil pengaturan info sekolah (nama sekolah, alamat, kop surat)
        $sekolah = SettingSekolah::first();

        // 7. Kembalikan ke tampilan (blade template) untuk mencetak rapor
        // Parameter compact(...) mengirimkan variabel-variabel di atas agar dikenali di file html-nya.
        return view('rapor.cetak-rapor', compact(
            'siswa', 'kelas', 'semester', 'jenisUjian', 'tahunAjaran', 'nilais', 'sekolah'
        ));
    }
}
