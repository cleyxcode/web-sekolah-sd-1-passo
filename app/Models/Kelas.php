<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = [];

    // ─── RELASI ────────────────────────────────────────────

    /** Wali kelas (relasi ke Guru) */
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    /** Tahun ajaran kelas ini */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /** Semua siswa di kelas ini */
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    /** Siswa aktif saja */
    public function siswasAktif()
    {
        return $this->hasMany(Siswa::class)->where('status', 'aktif');
    }

    /** Jadwal pelajaran kelas ini */
    public function jadwalPelajarans()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    /** Tugas yang diberikan ke kelas ini */
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    /** Semua nilai siswa di kelas ini */
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    /** Semua presensi di kelas ini */
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    // ─── HELPERS ───────────────────────────────────────────

    /** Label nama kelas dengan tingkat */
    public function getLabelAttribute(): string
    {
        return "Kelas {$this->nama_kelas}" . ($this->tingkat ? " (Tingkat {$this->tingkat})" : '');
    }

    /** Jumlah siswa aktif */
    public function getJumlahSiswaAttribute(): int
    {
        return $this->siswasAktif()->count();
    }
}
