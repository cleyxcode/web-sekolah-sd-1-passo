<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaporDetail extends Model
{
    protected $guarded = [];

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
}
