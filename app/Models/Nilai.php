<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $guarded = [];

    protected $casts = [
        'nilai_angka'    => 'float',
        'tahun_ajaran_id'=> 'integer',
        'siswa_id'       => 'integer',
        'kelas_id'       => 'integer',
        'guru_id'        => 'integer',
        'mata_pelajaran_id' => 'integer',
    ];

    // ─── RELASI ────────────────────────────────────────────

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    /** Guru yang menginput (wali kelas) */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // ─── HELPERS ───────────────────────────────────────────

    /** Predikat berdasarkan nilai angka */
    public function getPredikatAttribute(): string
    {
        return match(true) {
            $this->nilai_angka >= 90 => 'A',
            $this->nilai_angka >= 75 => 'B',
            $this->nilai_angka >= 60 => 'C',
            default                  => 'D',
        };
    }

    /** Keterangan predikat */
    public function getKeteranganAttribute(): string
    {
        return match($this->predikat) {
            'A'     => 'Sangat Baik',
            'B'     => 'Baik',
            'C'     => 'Cukup',
            default => 'Perlu Bimbingan',
        };
    }

    // ─── SCOPES ────────────────────────────────────────────

    public function scopeSemester($query, string $semester)
    {
        return $query->where('nilais.semester', $semester);
    }

    public function scopeJenisUjian($query, string $jenisUjian)
    {
        return $query->where('nilais.jenis_ujian', $jenisUjian);
    }

    public function scopeTahunAjaran($query, int $tahunAjaranId)
    {
        return $query->where('nilais.tahun_ajaran_id', $tahunAjaranId);
    }
}
