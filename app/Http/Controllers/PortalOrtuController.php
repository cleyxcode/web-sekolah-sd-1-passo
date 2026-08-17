<?php

// Menentukan alamat folder untuk file controller ini

namespace App\Http\Controllers;

use App\Models\Tugas;         // Menangani input formulir atau link dari pengguna
use Illuminate\Http\Request; // Menangani urusan login dan hak akses (otentikasi)
// Model Orang Tua
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

                // Model Tugas

/**
 * PortalOrtuController
 *
 * Mengatur semua halaman khusus orang tua wali murid,
 * mulai dari login, cek nilai anak, cek presensi, hingga ganti profil.
 */
class PortalOrtuController extends Controller
{
    /**
     * Menampilkan halaman login untuk orang tua
     */
    public function loginForm()
    {
        // Cek apakah orang tua ini sebelumnya sudah login (menggunakan guard 'ortu')
        // Guard 'ortu' digunakan untuk memisahkan login admin/guru dan login orang tua
        if (Auth::guard('ortu')->check()) {
            // Jika sudah login, langsung arahkan ke halaman dasbor orang tua
            return redirect()->route('portal.ortu.dashboard');
        }

        // Jika belum login, tampilkan halaman form login
        return view('pages.portal-ortu.login');
    }

    /**
     * Proses pengecekan email dan password saat orang tua mencoba login
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi inputan form dari pengguna
        // Email harus diisi dan format emailnya benar, password harus diisi
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba cocokkan data email dan password dengan tabel orang tua di database
        // $request->boolean('remember-me') digunakan jika user mencentang fitur "Ingat Saya"
        if (Auth::guard('ortu')->attempt($credentials, $request->boolean('remember-me'))) {
            // Jika cocok: perbarui keamanan sesi login (mencegah pencurian sesi)
            $request->session()->regenerate();

            // Arahkan ke halaman dasbor (halaman utama portal)
            return redirect()->intended(route('portal.ortu.dashboard'));
        }

        // 3. Jika email/password salah:
        // Kembalikan ke halaman login sebelumnya dengan membawa pesan error
        // onlyInput('email') artinya kolom email tetap terisi, sehingga tidak perlu ketik ulang email
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Proses keluar (Log Out) dari portal orang tua
     */
    public function logout(Request $request)
    {
        // Putus status login untuk guard 'ortu'
        Auth::guard('ortu')->logout();

        // Hancurkan data sesi sementara
        $request->session()->invalidate();
        // Buat token keamanan baru
        $request->session()->regenerateToken();

        // Arahkan pengguna kembali ke halaman depan website
        return redirect()->route('home');
    }

    /**
     * Menampilkan Dasbor (Halaman Utama) Portal Orang Tua
     */
    public function dashboard()
    {
        // Ambil data siapa orang tua yang sedang login saat ini
        $ortu = Auth::guard('ortu')->user();

        // Jika karena alasan tertentu datanya tidak ada, paksa login ulang
        if (! $ortu) {
            return redirect()->route('login.ortu');
        }

        // Ambil data semua ANAK (siswa) yang terhubung dengan orang tua ini
        // with([]) artinya memuat data relasi sekaligus agar database tidak lemot (Eager Loading)
        $anak_anak = $ortu->siswas()->with([
            'kelas.jadwalPelajarans.mataPelajaran', // Jadwal pelajaran kelas anak
            'kelas.jadwalPelajarans.guru',          // Guru di jadwal pelajaran
            'kelas.waliKelas',                      // Wali kelas si anak
            'nilais.mataPelajaran',                 // Nilai-nilai tugas/ujian anak
            // Ambil maksimal 30 presensi terakhir, urutkan dari yang terbaru
            'presensis' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(30);
            },
            // Ambil catatan perkembangan yang ditulis guru, dari yang terbaru
            'catatanPerkembangans.guru' => function ($q) {
                $q->latest();
            },
        ])->get();

        // Hitung statistik secara manual untuk masing-masing anak
        foreach ($anak_anak as $anak) {
            // Hitung jumlah kehadiran berdasarkan status
            $anak->stat_hadir = $anak->presensis->where('status', 'hadir')->count();
            $anak->stat_sakit = $anak->presensis->where('status', 'sakit')->count();
            $anak->stat_izin = $anak->presensis->where('status', 'izin')->count();
            $anak->stat_alpha = $anak->presensis->where('status', 'alpha')->count();
            $anak->stat_total = $anak->presensis->count();

            // Hitung presentase kehadiran (hadir dibagi total, dikali 100)
            $anak->pct_hadir = $anak->stat_total > 0
                ? round(($anak->stat_hadir / $anak->stat_total) * 100)
                : 0;

            // Saring presensi yang ada lampiran foto (foto bukti masuk/sakit)
            $anak->presensis_foto = $anak->presensis->whereNotNull('foto_absen')->values();

            // Cek apakah ada tugas yang sedang aktif untuk kelas anak ini
            if ($anak->kelas_id) {
                $anak->tugas_kelas = Tugas::where('kelas_id', $anak->kelas_id)
                    ->where('status', 'aktif') // Hanya tugas aktif (belum ditutup/lewat)
                    ->with(['guru', 'komentars.guru']) // Bawa info guru & komentar
                    ->orderBy('deadline', 'asc') // Urutkan yang mau tenggat waktu duluan
                    ->get();
            } else {
                // Jika anak ini tidak masuk kelas mana pun, tugas kosong
                $anak->tugas_kelas = collect();
            }
        }

        // Tampilkan halaman dasbor HTML
        return view('pages.portal-ortu.index', compact('ortu', 'anak_anak'));
    }

    /**
     * Tampilkan halaman pengaturan profil orang tua
     */
    public function profil()
    {
        $ortu = Auth::guard('ortu')->user();
        if (! $ortu) {
            return redirect()->route('login.ortu');
        }

        return view('pages.portal-ortu.profil', compact('ortu'));
    }

    /**
     * Proses penyimpanan perubahan data profil orang tua
     */
    public function updateProfil(Request $request)
    {
        $ortu = Auth::guard('ortu')->user();
        if (! $ortu) {
            return redirect()->route('login.ortu');
        }

        // Validasi input data dari orang tua
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            // Untuk password baru (harus diisi 2 kali agar confirmed, min 8 karakter)
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Ganti data lama dengan data baru yang dimasukkan
        $ortu->nama = $validated['nama'];
        $ortu->no_telepon = $validated['no_telepon'];
        $ortu->pekerjaan = $validated['pekerjaan'];
        $ortu->alamat = $validated['alamat'];

        // Jika kotak password diisi (artinya mau ganti password)
        if (! empty($validated['password'])) {
            // Hash (acak) sandi menggunakan algoritma enkripsi standar Laravel
            $ortu->password = Hash::make($validated['password']);
        }

        // Simpan perubahan ke database
        $ortu->save();

        // Kembali ke halaman profil dengan menampilkan pesan sukses
        return back()->with('success', 'Profil dan pengaturan berhasil diperbarui!');
    }
}
