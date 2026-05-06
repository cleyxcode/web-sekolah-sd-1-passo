<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model Guru
 *
 * Mewakili tabel 'gurus' di database.
 * Satu baris di tabel = satu data guru.
 *
 * RELASI:
 *   - belongsTo User              → Setiap guru punya SATU akun login (User)
 *   - hasOne Kelas (wali)         → Guru bisa menjadi wali kelas di SATU kelas
 *   - hasMany GuruMataPelajaran   → Guru mengajar BANYAK mata pelajaran (via pivot)
 *   - belongsToMany MataPelajaran → Guru bisa mengajar BANYAK mata pelajaran
 *   - hasMany Nilai               → Guru menginput BANYAK nilai
 *   - hasMany Presensi            → Guru mencatat BANYAK presensi
 *   - hasMany Tugas               → Guru membuat BANYAK tugas
 *   - hasMany CatatanPerkembangan → Guru membuat BANYAK catatan perkembangan siswa
 */
class Guru extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar (tidak ada yang diproteksi)
    protected $guarded = [];

    // Mendefinisikan casting tipe data untuk kolom tertentu
    protected $casts = [
        // Kolom 'tampil_di_website' disimpan sebagai 0/1 di database,
        // tapi saat dibaca PHP akan menjadi true/false (boolean)
        'tampil_di_website' => 'boolean',
    ];

    // ─── RELASI ────────────────────────────────────────────

    /**
     * RELASI: Guru MEMILIKI satu akun User (One-to-One)
     * Artinya: setiap guru harus punya akun untuk login ke sistem
     * Kolom foreign key: 'user_id' di tabel gurus
     *
     * Cara pakai: $guru->user → mengambil data akun login guru
     */
    public function user()
    {
        // belongsTo = "saya milik satu" → guru milik satu akun user
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI: Guru menjadi Wali Kelas di satu Kelas (One-to-One)
     * Artinya: satu guru hanya bisa menjadi wali kelas di satu kelas
     * Kolom foreign key yang dirujuk: 'wali_kelas_id' di tabel kelas
     *
     * Cara pakai: $guru->kelasWali → mengambil data kelas yang diwali guru ini
     * Jika null, berarti guru ini bukan wali kelas
     */
    public function kelasWali()
    {
        // hasOne = "saya punya satu" → guru punya satu kelas wali
        // Parameter kedua 'wali_kelas_id' = nama foreign key di tabel kelas
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    /**
     * RELASI: Guru mengajar banyak MataPelajaran (via tabel pivot GuruMataPelajaran)
     * Ini mengakses tabel pivot secara langsung sebagai model
     *
     * Cara pakai: $guru->mataPelajarans → koleksi entri pivot guru-mapel
     */
    public function mataPelajarans()
    {
        return $this->hasMany(GuruMataPelajaran::class);
    }

    /**
     * RELASI: Guru mengajar banyak MataPelajaran (Many-to-Many)
     * Tabel penghubung (pivot): 'guru_mata_pelajarans'
     * Guru bisa mengajar banyak mata pelajaran, dan satu mata pelajaran bisa diajar banyak guru
     *
     * Cara pakai: $guru->mapelDiajar → koleksi semua mata pelajaran yang diajar guru ini
     */
    public function mapelDiajar()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mata_pelajarans')
            ->withPivot(['kelas_id', 'tahun_ajaran_id']) // Ambil juga data tambahan di pivot (kelas & tahun ajaran)
            ->withTimestamps(); // Otomatis simpan waktu created_at dan updated_at di pivot
    }

    /**
     * RELASI: Guru menginput banyak Nilai (One-to-Many)
     * Artinya: satu guru bisa menginput nilai untuk banyak siswa/mata pelajaran
     *
     * Cara pakai: $guru->nilais → semua nilai yang diinput guru ini
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * RELASI: Guru mencatat banyak Presensi (One-to-Many)
     * Artinya: satu guru bisa mencatat kehadiran untuk banyak siswa/hari
     *
     * Cara pakai: $guru->presensis → semua catatan presensi oleh guru ini
     */
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    /**
     * RELASI: Guru membuat banyak Tugas (One-to-Many)
     * Artinya: satu guru bisa membuat banyak tugas untuk kelasnya
     *
     * Cara pakai: $guru->tugas → semua tugas yang dibuat guru ini
     */
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    /**
     * RELASI: Guru membuat banyak CatatanPerkembangan (One-to-Many)
     * Artinya: satu guru bisa menulis banyak catatan perkembangan untuk siswa-siswanya
     *
     * Cara pakai: $guru->catatanPerkembangans → semua catatan perkembangan oleh guru ini
     */
    public function catatanPerkembangans()
    {
        return $this->hasMany(CatatanPerkembangan::class);
    }

    // ─── HELPERS ───────────────────────────────────────────

    /**
     * HELPER: Mengecek apakah guru ini sedang menjadi wali kelas
     * @return bool true jika guru adalah wali kelas, false jika tidak
     *
     * Cara pakai: $guru->isWaliKelas() → true atau false
     */
    public function isWaliKelas(): bool
    {
        // Cek apakah ada data kelas yang memiliki wali_kelas_id = id guru ini
        return $this->kelasWali()->exists();
    }

    /**
     * ACCESSOR: Menghasilkan URL lengkap foto guru
     * Jika tidak ada foto, akan mengembalikan null
     *
     * Cara pakai: $guru->foto_url → URL lengkap foto
     */
    public function getFotoUrlAttribute(): ?string
    {
        // Jika kolom 'foto' kosong/null, langsung kembalikan null
        if (!$this->foto) return null;

        // Ubah path relatif di storage menjadi URL yang bisa diakses browser
        return \Illuminate\Support\Facades\Storage::url($this->foto);
    }
}
