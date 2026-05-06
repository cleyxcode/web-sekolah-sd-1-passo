<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

// Mengimpor Carbon untuk operasi tanggal dan waktu
use Carbon\Carbon;

/**
 * Model Tugas
 *
 * Mewakili tabel 'tugas' di database.
 * Satu baris = satu tugas yang diberikan guru kepada siswa.
 *
 * RELASI:
 *   - belongsTo Kelas → Tugas ini diberikan kepada SATU kelas
 *   - belongsTo Guru  → Tugas ini dibuat oleh SATU guru
 *   - hasMany KomentarTugas → Satu tugas bisa punya BANYAK komentar
 */
class Tugas extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    // Mendefinisikan casting tipe data untuk setiap kolom penting
    protected $casts = [
        // Kolom 'deadline' akan otomatis dikonversi ke objek Carbon (tanggal/waktu)
        // Sehingga bisa digunakan method seperti isPast(), diffForHumans(), dll
        'deadline'   => 'datetime',

        // Kolom 'foto_tugas' disimpan sebagai JSON string di database,
        // tapi saat dibaca PHP akan otomatis menjadi array
        'foto_tugas' => 'array',

        // Sama seperti foto_tugas, 'file_tugas' juga disimpan sebagai JSON array
        'file_tugas' => 'array',
    ];

    /**
     * RELASI: Tugas ini diberikan kepada satu Kelas (Many-to-One)
     * Artinya: banyak tugas bisa diberikan ke satu kelas yang sama
     * Kolom foreign key: 'kelas_id' di tabel tugas
     *
     * Cara pakai: $tugas->kelas → mengambil data kelas penerima tugas ini
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: Tugas ini dibuat oleh satu Guru (Many-to-One)
     * Kolom foreign key: 'guru_id' di tabel tugas
     *
     * Cara pakai: $tugas->guru → mengambil data guru yang membuat tugas ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * RELASI: Tugas memiliki banyak KomentarTugas (One-to-Many)
     * Artinya: satu tugas bisa punya banyak komentar (dari guru atau siswa)
     * Diurutkan dari komentar terbaru (latest = ORDER BY created_at DESC)
     *
     * Cara pakai: $tugas->komentars → semua komentar di tugas ini, terbaru dulu
     */
    public function komentars()
    {
        return $this->hasMany(KomentarTugas::class)->latest(); // latest() = urutkan dari terbaru
    }

    /**
     * SCOPE: Filter hanya tugas yang statusnya 'aktif'
     * Scope adalah cara pintas untuk menyaring data
     *
     * Cara pakai: Tugas::aktif()->get() → hanya tugas yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif'); // Tambahkan kondisi WHERE status = 'aktif'
    }

    /**
     * SCOPE: Urutkan tugas berdasarkan deadline terdekat (yang hampir jatuh tempo duluan)
     *
     * Cara pakai: Tugas::deadlineTerdekat()->get() → tugas yang paling dekat deadlinenya di atas
     */
    public function scopeDeadlineTerdekat($query)
    {
        return $query->orderBy('deadline', 'asc'); // ASC = dari terkecil (terdekat) ke terbesar
    }

    /**
     * ACCESSOR: Mengecek apakah tugas ini sudah melewati deadline
     * @return bool true = sudah lewat deadline, false = masih bisa dikumpulkan
     *
     * Cara pakai: $tugas->sudah_lewat_deadline → true atau false
     */
    public function getSudahLewatDeadlineAttribute(): bool
    {
        // isPast() = apakah tanggal ini sudah lewat dari sekarang?
        return $this->deadline->isPast();
    }

    /**
     * ACCESSOR: Menampilkan sisa waktu pengumpulan dalam format yang mudah dibaca
     * Contoh output: "2 hari lagi", "3 jam lagi", "Sudah lewat"
     *
     * Cara pakai: $tugas->sisa_waktu → teks sisa waktu
     */
    public function getSisaWaktuAttribute(): string
    {
        // Jika deadline sudah lewat, tampilkan pesan ini
        if ($this->deadline->isPast()) {
            return 'Sudah lewat';
        }

        // diffForHumans = hitung selisih waktu dalam format mudah dibaca
        // ['parts' => 2] = tampilkan 2 bagian, contoh: "2 hari 3 jam lagi"
        return $this->deadline->diffForHumans(['parts' => 2]);
    }

    /**
     * ACCESSOR: Menentukan warna badge berdasarkan sisa waktu deadline
     * Digunakan untuk tampilan visual di portal (merah = gawat, hijau = aman)
     *
     * Cara pakai: $tugas->deadline_color → 'merah', 'oranye', 'kuning', atau 'hijau'
     */
    public function getDeadlineColorAttribute(): string
    {
        // Jika sudah lewat deadline, beri warna merah (tanda bahaya)
        if ($this->deadline->isPast()) {
            return 'merah';
        }

        // Hitung sisa waktu dalam satuan jam
        $hariSisa = now()->diffInHours($this->deadline);

        // Jika sisa waktu kurang dari 24 jam, beri warna oranye (hampir habis)
        if ($hariSisa <= 24) {
            return 'oranye';
        }

        // Jika sisa waktu kurang dari 72 jam (3 hari), beri warna kuning (perhatian)
        if ($hariSisa <= 72) {
            return 'kuning';
        }

        // Jika masih jauh dari deadline, beri warna hijau (aman)
        return 'hijau';
    }
}
