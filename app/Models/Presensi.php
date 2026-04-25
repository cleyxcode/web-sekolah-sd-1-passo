<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($presensi) {
            if (empty($presensi->kelas_id) && !empty($presensi->siswa_id)) {
                $siswa = $presensi->siswa ?? Siswa::find($presensi->siswa_id);
                if ($siswa) {
                    $presensi->kelas_id = $siswa->kelas_id;
                }
            }
        });

        static::updating(function ($presensi) {
            if ($presensi->isDirty('siswa_id')) {
                $siswa = $presensi->siswa ?? Siswa::find($presensi->siswa_id);
                if ($siswa) {
                    $presensi->kelas_id = $siswa->kelas_id;
                }
            }
        });
    }

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
