<?php

// Menentukan lokasi folder file controller ini
namespace App\Http\Controllers;

// Mengimpor kelas Request untuk menangani input dari pengguna (seperti dari form atau URL)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

// Mengimpor model-model database yang dibutuhkan
use App\Models\Berita;        // Model untuk mengambil data berita
use App\Models\ProfilSekolah; // Model untuk profil, visi, misi, dan sejarah
use App\Models\Galeri;        // Model untuk foto-foto galeri

/**
 * HomeController
 * 
 * Bertugas mengatur tampilan halaman depan (publik) pada website sekolah.
 * Kelas ini turunan dari Controller utama.
 */
class HomeController extends Controller
{
    /**
     * Ambil data profil guru dengan fallback aman untuk production.
     * Mencegah error 500 jika kolom tambahan belum ada di server.
     */
    private function getProfilGuruForHome()
    {
        try {
            $query = \App\Models\Guru::query();

            if (Schema::hasColumn('gurus', 'tampil_di_website')) {
                $query->where('tampil_di_website', true);
            }

            if (Schema::hasColumn('gurus', 'urutan_tampil')) {
                $query->orderBy('urutan_tampil');
            }

            return $query->orderBy('nama')->get();
        } catch (\Throwable $e) {
            Log::error('Gagal memuat profil guru untuk beranda', [
                'message' => $e->getMessage(),
            ]);

            // Fallback terakhir: tetap tampilkan data guru dasar jika memungkinkan.
            return \App\Models\Guru::orderBy('nama')->get();
        }
    }

    /**
     * Menampilkan halaman Beranda (Home) utama
     */
    public function index()
    {
        // 1. Ambil 3 berita terbaru yang statusnya 'publish' (siap tayang)
        // orderBy('published_at', 'desc') artinya urutkan dari yang terbaru
        // take(3) artinya hanya ambil 3 berita saja
        // get() artinya eksekusi query ke database
        $berita = Berita::where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
            
        // 2. Ambil data Visi dan Misi sekolah dari tabel profil sekolah
        // first() artinya ambil satu baris data saja (yang paling pertama cocok)
        $visi = ProfilSekolah::where('jenis', 'visi')->first();
        $misi = ProfilSekolah::where('jenis', 'misi')->first();
        
        // 3. Ambil 4 foto terbaru dari galeri
        // latest() = urutkan berdasarkan waktu ditambahkan terbaru (created_at desc)
        $galeri = Galeri::latest()->take(4)->get();

        // 4. Ambil info pendaftaran yang sedang aktif (is_active = true)
        // Pakai path model lengkap: \App\Models\Pendaftaran karena belum di-use di atas
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        // 5. Ambil data guru/staf untuk ditampilkan di bagian "Profil Guru" (slideshow)
        // Hanya ambil guru yang diset tampil di website (tampil_di_website = true)
        // Diurutkan berdasarkan 'urutan_tampil', lalu berdasarkan abjad 'nama'
        $profil_guru = $this->getProfilGuruForHome();
        
        // Kembalikan tampilan halaman (file blade) 'pages.home.index'
        // compact() digunakan untuk mengirimkan variabel-variabel di atas ke dalam tampilan (view) HTML
        return view('pages.home.index', compact('berita', 'visi', 'misi', 'galeri', 'pendaftaran', 'profil_guru'));
    }

    /**
     * Menampilkan halaman Profil Sekolah secara utuh (Sejarah, Visi, Misi)
     */
    public function profil()
    {
        // Ambil data Visi, Misi, dan Sejarah secara satuan
        $visi = ProfilSekolah::where('jenis', 'visi')->first();
        $misi = ProfilSekolah::where('jenis', 'misi')->first();
        $sejarah = ProfilSekolah::where('jenis', 'sejarah')->first();
        
        // Cek juga info pendaftaran aktif (misal untuk tombol di header)
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        // Tampilkan halaman profil (file: resources/views/pages/profil/index.blade.php)
        return view('pages.profil.index', compact('visi', 'misi', 'sejarah', 'pendaftaran'));
    }

    /**
     * Menampilkan daftar semua Berita (dengan paging / dibagi per halaman)
     */
    public function berita()
    {
        // Ambil berita yang dipublish, diurutkan terbaru
        // paginate(9) artinya bagi berita tersebut, 1 halaman isinya maksimal 9 berita
        $berita = Berita::where('status', 'publish')->orderBy('published_at', 'desc')->paginate(9);
        
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        // Tampilkan daftar berita
        return view('pages.berita.index', compact('berita', 'pendaftaran'));
    }

    /**
     * Menampilkan isi lengkap satu berita (Detail Berita)
     * Menggunakan Route Model Binding: parameter $berita langsung otomatis dicari di database
     */
    public function beritaDetail(Berita $berita)
    {
        // Cegah akses jika status berita belum 'publish' (misal masih 'draft')
        if ($berita->status !== 'publish') {
            abort(404); // Munculkan halaman error 404 (Not Found)
        }

        // Ambil 3 berita lainnya untuk rekomendasi "Berita Lainnya" di samping konten
        // where('id', '!=', $berita->id) agar berita yang sedang dibaca ini tidak muncul lagi di rekomendasi
        $beritaLainnya = Berita::where('status', 'publish')
            ->where('id', '!=', $berita->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        // Tampilkan tampilan detail berita
        return view('pages.berita.detail', compact('berita', 'beritaLainnya', 'pendaftaran'));
    }

    /**
     * Menampilkan daftar foto Galeri
     */
    public function galeri()
    {
        // Ambil semua galeri secara terbaru, dibagi 12 gambar per halaman
        $galeri = Galeri::latest()->paginate(12);
        
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        // Tampilkan halaman galeri
        return view('pages.galeri.index', compact('galeri', 'pendaftaran'));
    }

    /**
     * Menampilkan halaman khusus Info Pendaftaran Siswa Baru
     */
    public function pendaftaran()
    {
        // Cek apakah ada pengumuman pendaftaran yang sedang aktif
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        // Jika tidak ada pendaftaran yang dibuka...
        if (!$pendaftaran) {
            // Arahkan pengunjung kembali ke halaman depan dengan pesan error
            return redirect()->route('home')->with('error', 'Pendaftaran belum dibuka.');
        }

        // Jika pendaftaran dibuka, arahkan pengunjung LANGSUNG ke link URL Google Form / formulirnya
        return redirect()->away($pendaftaran->link_pendaftaran);
    }
}
