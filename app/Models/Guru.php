<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mataPelajarans()
    {
        return $this->hasMany(GuruMataPelajaran::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
