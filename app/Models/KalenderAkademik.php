<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderAkademik extends Model
{
    protected $guarded = [];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
