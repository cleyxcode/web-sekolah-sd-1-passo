<?php

// Menentukan letak folder file controller ini

namespace App\Http\Controllers;

// Mengimpor model-model yang diperlukan untuk menarik data dari database
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\SettingSekolah;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;         // Menangani request / input dari link URL
use Illuminate\Support\Facades\Auth; // Menangani sistem login dan pengecekan pengguna

/**
 * RekapKelasController
 *
 * Mengatur tampilan cetak lembar "Rekap Nilai Kelas" (Leger/Ledger).
 * File ini digunakan oleh pihak sekolah (terutama wali kelas)
 * untuk mencetak satu tabel panjang yang berisi semua nama siswa beserta nilai semua mata pelajaran mereka.
 */
class RekapKelasController extends Controller
{
    /**
     * Tampilkan halaman cetak rekap nilai kelas.
     * Diakses melalui link / URL dengan memberikan parameter query, contoh:
     * ?kelas_id=1&semester=1&jenis_ujian=UAS&tahun_ajaran_id=2
     */
    public function cetak(Request $request)
    {
        // 1. Ambil data orang (Admin/Guru) yang sedang login saat ini
        $user = Auth::user();

        // Jika tidak ada user yang login, hentikan proses (Akses Ditolak)
        if (! $user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        // 2. Tangkap parameter / syarat pencarian dari link URL
        // $request->query('nama_parameter') artinya mengambil nilai di belakang tanda '?' pada link URL
        $kelasId = $request->query('kelas_id');         // ID kelas yang akan dicetak
        $semester = $request->query('semester');         // Semester Ganjil / Genap
        $jenisUjian = $request->query('jenis_ujian');      // UTS / UAS
        $tahunAjaranId = $request->query('tahun_ajaran_id');  // ID tahun ajaran

        // 3. Pengecekan Keamanan dan Hak Akses (Otorisasi)
        // Cek apakah user yang sedang login punya jabatan tinggi
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isKepsek = $user->hasRole('Kepala Sekolah');

        // Jika BUKAN Super Admin dan BUKAN Kepala Sekolah (berarti kemungkinan besar Wali Kelas)
        if (! $isSuperAdmin && ! $isKepsek) {
            // Cek data profil guru yang nyambung dengan akun login ini
            $guru = Guru::where('user_id', $user->id)->first();

            // Kalau profil gurunya tidak ketemu (mungkin staf TU biasa tanpa hak), tolak akses
            if (! $guru) {
                abort(403, 'Anda tidak memiliki akses.');
            }

            // Cari tahu, kelas apa saja yang mana guru ini menjadi wali kelasnya?
            // pluck('id') artinya hanya ambil daftar angka ID kelasnya saja.
            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');

            // PENTING: Cek apakah kelas yang mau dicetak ($kelasId) ada di daftar kelas perwaliannya ($kelasIds)?
            // Kalau ternyata dia guru kelas 2, tapi maksa mau cetak nilai kelas 1, tolak aksesnya!
            if (! $kelasIds->contains($kelasId)) {
                abort(403, 'Anda hanya bisa mencetak rekap nilai kelas yang Anda wali.');
            }
        }

        // 4. Proses Menarik Data untuk Cetakan (Tabel Rekap Nilai)

        // Ambil data detail mengenai Kelas yang diminta, sekalian bawa data Wali Kelasnya (untuk tanda tangan)
        // findOrFail artinya "Cari dan dapatkan, kalau tidak ketemu langsung munculkan Error 404"
        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);

        // Ambil data tahun ajaran
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);

        // Ambil data informasi sekolah (Kop surat, nama Kepala Sekolah)
        $sekolah = SettingSekolah::first();

        // 5. Ambil daftar semua nama SISWA yang terdaftar di kelas tersebut
        // Hanya yang berstatus 'aktif' yang dicetak, dan urutkan sesuai abjad A-Z
        $siswas = Siswa::where('kelas_id', $kelasId)
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        // 6. Ambil SEMUA NILAI di kelas itu untuk semester & ujian yang diminta
        // with('mataPelajaran') = sekalian panggil nama mata pelajarannya biar cepat
        $allNilais = Nilai::with('mataPelajaran')
            ->where('kelas_id', $kelasId)
            ->where('semester', $semester)
            ->where('jenis_ujian', $jenisUjian)
            // when() = "Jika ada id tahun ajaran yang dicari, maka cari juga berdasarkan id itu"
            ->when($tahunAjaranId, fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->get();

        // 7. Proses Penyusunan/Pengelompokkan Data agar mudah dibaca pada tabel (Kolom & Baris)

        // Kelompokkan data nilai tadi berdasarkan SIAPA pemiliknya (berdasarkan id siswa)
        // Jadi formatnya seperti: [ "Siswa_A" => [Nilai_Matematika, Nilai_IPA], "Siswa_B" => [Nilai_Matematika...] ]
        $nilaisGrouped = $allNilais->groupBy('siswa_id');

        // Kumpulkan apa saja ID mata pelajaran yang keluar / ada nilainya di kelas ini
        // pluck -> ambil list ID saja, unique() -> hapus angka yang dobel/kembar
        $mataPelajaranIds = $allNilais->pluck('mata_pelajaran_id')->unique();

        // Dari kumpulan ID mapel di atas, ambil data nama mata pelajarannya dari database
        // dan urutkan secara abjad agar di judul kolom tabel terlihat rapi
        $mataPelajarans = MataPelajaran::whereIn('id', $mataPelajaranIds)
            ->orderBy('nama')
            ->get();

        // 8. Kirim semua data yang sudah diracik tadi menuju ke file tampilan HTML (blade template)
        // Tepatnya akan menampilkan file: resources/views/rapor/rekap-kelas.blade.php
        return view('rapor.rekap-kelas', compact(
            'kelas', 'semester', 'jenisUjian', 'tahunAjaran',
            'siswas', 'nilaisGrouped', 'mataPelajarans', 'sekolah'
        ));
    }
}
