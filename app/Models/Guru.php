<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tampil_di_website' => 'boolean',
    ];

    // ─── RELASI ────────────────────────────────────────────

    /** Akun user yang terhubung dengan guru ini */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Kelas yang di-walikan guru ini */
    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    /** Mapping mata pelajaran yang diajarkan (pivot GuruMataPelajaran) */
    public function mataPelajarans()
    {
        return $this->hasMany(GuruMataPelajaran::class);
    }

    /** Relasi many-to-many ke MataPelajaran via pivot */
    public function mapelDiajar()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mata_pelajarans')
            ->withPivot(['kelas_id', 'tahun_ajaran_id'])
            ->withTimestamps();
    }

    /** Semua nilai yang diinput guru ini */
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    /** Semua presensi yang dicatat guru ini */
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    /** Semua tugas yang dibuat guru ini */
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    /** Semua catatan perkembangan yang dibuat guru ini */
    public function catatanPerkembangans()
    {
        return $this->hasMany(CatatanPerkembangan::class);
    }

    // ─── HELPERS ───────────────────────────────────────────

    /** Apakah guru ini adalah wali kelas? */
    public function isWaliKelas(): bool
    {
        return $this->kelasWali()->exists();
    }

    /** URL foto guru */
    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) return null;
        return \Illuminate\Support\Facades\Storage::url($this->foto);
    }
}
