<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = [];

    // ─── RELASI ────────────────────────────────────────────

    /** Kelas saat ini */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /** Tahun ajaran aktif siswa */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /** Orang tua / wali (many-to-many) */
    public function orangTuas()
    {
        return $this->belongsToMany(OrangTua::class, 'orang_tua_siswas')
            ->withTimestamps();
    }

    /** Semua nilai akademik siswa */
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    /** Semua catatan presensi siswa */
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    /** Semua catatan perkembangan siswa */
    public function catatanPerkembangans()
    {
        return $this->hasMany(CatatanPerkembangan::class);
    }

    /** Riwayat perpindahan kelas */
    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class);
    }

    // ─── SCOPES ────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // ─── HELPERS ───────────────────────────────────────────

    /** URL foto siswa */
    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) return null;
        return \Illuminate\Support\Facades\Storage::url($this->foto);
    }

    /** Rata-rata nilai untuk semester & ujian tertentu */
    public function getRataRataNilai(?string $semester = null, ?string $jenisUjian = null, ?int $tahunAjaranId = null): float
    {
        $query = $this->nilais();
        if ($semester)     $query->where('semester', $semester);
        if ($jenisUjian)   $query->where('jenis_ujian', $jenisUjian);
        if ($tahunAjaranId) $query->where('tahun_ajaran_id', $tahunAjaranId);
        return round($query->avg('nilai_angka') ?? 0, 1);
    }
}
