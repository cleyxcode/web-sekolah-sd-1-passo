<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Accessor: URL lengkap foto absen untuk ditampilkan di portal orang tua
     */
    public function getFotoAbsenUrlAttribute(): ?string
    {
        if (!$this->foto_absen) return null;
        return \Illuminate\Support\Facades\Storage::url($this->foto_absen);
    }
}
