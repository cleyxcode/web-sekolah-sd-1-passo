<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model Presensi
 *
 * Mewakili tabel 'presensis' di database.
 * Satu baris = satu catatan kehadiran siswa pada satu hari tertentu.
 *
 * Nilai status kehadiran biasanya: 'hadir', 'izin', 'sakit', 'alpha' (tanpa keterangan)
 *
 * RELASI:
 *   - belongsTo Siswa      → Presensi ini milik SATU siswa
 *   - belongsTo Kelas      → Presensi ini di SATU kelas
 *   - belongsTo Guru       → Presensi ini dicatat oleh SATU guru
 *   - belongsTo TahunAjaran → Presensi ini di SATU tahun ajaran
 *
 * FITUR KHUSUS (booted):
 *   Secara otomatis mengisi 'kelas_id' dari data siswa saat presensi dibuat/diupdate,
 *   sehingga admin tidak perlu memasukkan kelas_id secara manual
 */
class Presensi extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * Method booted() dipanggil otomatis saat model di-load oleh Laravel
     * Di sini kita mendaftarkan "event listener" (pendengar kejadian) pada model
     */
    protected static function booted()
    {
        /**
         * Event 'creating' = dipanggil SEBELUM data presensi baru disimpan ke database
         * Fungsi ini mengisi otomatis kolom 'kelas_id' dari data siswa
         */
        static::creating(function ($presensi) {
            // Cek apakah kelas_id belum terisi DAN siswa_id sudah ada
            if (empty($presensi->kelas_id) && !empty($presensi->siswa_id)) {
                // Ambil data siswa: coba dari relasi yang sudah dimuat, atau query ulang ke database
                $siswa = $presensi->siswa ?? Siswa::find($presensi->siswa_id);

                // Jika data siswa ditemukan, ambil kelas_id dari data siswa tersebut
                if ($siswa) {
                    $presensi->kelas_id = $siswa->kelas_id; // Isi otomatis kelas_id
                }
            }
        });

        /**
         * Event 'updating' = dipanggil SEBELUM data presensi yang sudah ada diupdate
         * Fungsi ini memperbarui kelas_id jika siswa_id berubah
         */
        static::updating(function ($presensi) {
            // Cek apakah kolom 'siswa_id' sedang diubah (isDirty = ada perubahan yang belum disimpan)
            if ($presensi->isDirty('siswa_id')) {
                // Ambil data siswa baru
                $siswa = $presensi->siswa ?? Siswa::find($presensi->siswa_id);

                // Update kelas_id sesuai dengan kelas siswa yang baru dipilih
                if ($siswa) {
                    $presensi->kelas_id = $siswa->kelas_id;
                }
            }
        });
    }

    /**
     * RELASI: Presensi ini milik satu Siswa (Many-to-One)
     * Kolom foreign key: 'siswa_id' di tabel presensis
     *
     * Cara pakai: $presensi->siswa → mengambil data siswa dari presensi ini
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: Presensi ini berada di satu Kelas (Many-to-One)
     * Kolom foreign key: 'kelas_id' di tabel presensis
     *
     * Cara pakai: $presensi->kelas → mengambil data kelas dari presensi ini
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * RELASI: Presensi ini dicatat oleh satu Guru (Many-to-One)
     * Kolom foreign key: 'guru_id' di tabel presensis
     *
     * Cara pakai: $presensi->guru → mengambil data guru yang mencatat presensi ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * RELASI: Presensi ini berada di satu TahunAjaran (Many-to-One)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel presensis
     *
     * Cara pakai: $presensi->tahunAjaran → mengambil data tahun ajaran dari presensi ini
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * ACCESSOR: Menghasilkan URL lengkap foto bukti absen
     * Berguna untuk portal orang tua yang ingin melihat foto absen anaknya
     *
     * Cara pakai: $presensi->foto_absen_url → URL lengkap foto, atau null jika tidak ada
     */
    public function getFotoAbsenUrlAttribute(): ?string
    {
        // Jika tidak ada foto, langsung kembalikan null
        if (!$this->foto_absen) return null;

        // Ubah path relatif di storage menjadi URL yang bisa diakses browser
        return \Illuminate\Support\Facades\Storage::url($this->foto_absen);
    }
}
