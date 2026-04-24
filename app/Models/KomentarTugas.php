<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarTugas extends Model
{
    protected $table = 'komentar_tugas';
    protected $guarded = [];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
