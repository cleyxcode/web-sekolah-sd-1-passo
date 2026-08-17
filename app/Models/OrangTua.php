<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor Authenticatable agar OrangTua bisa login ke portal orang tua
use Illuminate\Foundation\Auth\User as Authenticatable;
// Notifiable memungkinkan model ini menerima notifikasi (email, dll)
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

/**
 * Model OrangTua
 *
 * Mewakili tabel 'orang_tuas' di database.
 * Satu baris = satu akun orang tua / wali murid.
 *
 * OrangTua bisa login ke portal orang tua untuk melihat nilai dan presensi anaknya.
 * Orang tua bisa memiliki lebih dari satu anak yang terdaftar di sekolah ini.
 *
 * RELASI:
 *   - belongsToMany Siswa → Satu orang tua bisa memiliki BANYAK anak (siswa)
 *     (dan satu siswa bisa punya lebih dari satu orang tua: ayah, ibu, wali)
 */
class OrangTua extends Authenticatable
{
    // Mengaktifkan fitur notifikasi (pengiriman email, dll) untuk model ini
    use Notifiable;

    // Semua kolom boleh diisi KECUALI 'id' yang merupakan primary key (auto-generated)
    protected $guarded = ['id'];

    // Kolom-kolom ini TIDAK akan ditampilkan saat data dikirim sebagai JSON
    // Penting untuk menyembunyikan password dan token dari client
    protected $hidden = ['password', 'remember_token'];

    // Mendefinisikan casting tipe data untuk kolom tertentu
    protected $casts = [
        // Kolom 'email_verified_at' dikonversi menjadi objek Carbon (tanggal/waktu)
        'email_verified_at' => 'datetime',
    ];

    // ─── RELASI ────────────────────────────────────────────

    /**
     * RELASI: OrangTua memiliki banyak Siswa (Many-to-Many)
     * Tabel penghubung (pivot): 'orang_tua_siswas'
     *
     * Artinya:
     *   - Satu orang tua bisa punya banyak anak (siswa) di sekolah ini
     *   - Satu siswa bisa punya lebih dari satu orang tua terdaftar (ayah, ibu, atau wali)
     *
     * Cara pakai: $orangTua->siswas → koleksi semua anak (siswa) milik orang tua ini
     */
    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'orang_tua_siswas')
            ->withTimestamps(); // Otomatis simpan waktu created_at dan updated_at di pivot
    }

    /**
     * HELPER: Mengambil data siswa pertama (untuk orang tua yang hanya punya satu anak)
     * Ini bukan relasi Eloquent, tapi shortcut untuk akses cepat
     *
     * Cara pakai: $orangTua->siswa() → data siswa pertama milik orang tua ini
     * Catatan: Jika orang tua punya banyak anak, gunakan siswas() sebagai gantinya
     */
    public function siswa()
    {
        // Ambil data dari relasi siswas(), kemudian ambil hanya yang pertama
        return $this->siswas()->first();
    }

    // ─── HELPERS ───────────────────────────────────────────

    /**
     * ACCESSOR: Menghasilkan URL lengkap foto orang tua
     * Cara pakai: $orangTua->foto_url → URL lengkap foto
     * Mengembalikan null jika tidak ada foto
     */
    public function getFotoUrlAttribute(): ?string
    {
        // Cek apakah kolom foto ada dan tidak kosong
        if (! isset($this->foto) || ! $this->foto) {
            return null;
        }

        // Ubah path relatif di storage menjadi URL yang bisa diakses browser
        return Storage::url($this->foto);
    }

    /**
     * ACCESSOR: Mengambil nama anak pertama sebagai shortcut tampilan
     * Berguna untuk menampilkan "Orang tua dari [nama anak]" di daftar
     *
     * Cara pakai: $orangTua->nama_anak → nama siswa pertama, atau '-' jika tidak ada
     */
    public function getNamaAnakAttribute(): string
    {
        // Ambil nama siswa pertama; jika tidak ada, tampilkan tanda '-'
        return $this->siswas()->first()?->nama ?? '-';
    }
}
