<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadDaftarHadir extends Model
{
    protected $guarded = [];

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
}
