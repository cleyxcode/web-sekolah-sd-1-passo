<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function orangTuas()
    {
        return $this->belongsToMany(OrangTua::class, 'orang_tua_siswas');
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    public function catatanPerkembangans()
    {
        return $this->hasMany(CatatanPerkembangan::class);
    }
}
