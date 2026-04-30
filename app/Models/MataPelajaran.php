<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $guarded = [];

    // ─── RELASI ────────────────────────────────────────────

    /** Semua nilai untuk mata pelajaran ini */
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    /** Guru yang mengajar mata pelajaran ini (via pivot) */
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mata_pelajarans')
            ->withPivot(['kelas_id', 'tahun_ajaran_id'])
            ->withTimestamps();
    }

    /** Jadwal pelajaran terkait mata pelajaran ini */
    public function jadwalPelajarans()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
}
