<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTuaSiswa extends Model
{
    protected $guarded = [];

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
