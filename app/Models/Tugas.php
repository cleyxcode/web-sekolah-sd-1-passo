<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tugas extends Model
{
    protected $guarded = [];

    protected $casts = [
        'deadline' => 'datetime',
        'foto_tugas' => 'array',
        'file_tugas' => 'array',
    ];

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke Guru (yang membuat tugas)
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi ke Komentar Tugas
    public function komentars()
    {
        return $this->hasMany(KomentarTugas::class)->latest();
    }

    // Scope: tugas yang masih aktif (deadline belum lewat)
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope: urutkan berdasarkan deadline terdekat
    public function scopeDeadlineTerdekat($query)
    {
        return $query->orderBy('deadline', 'asc');
    }

    // Attribute: apakah tugas sudah lewat deadline
    public function getSudahLewatDeadlineAttribute(): bool
    {
        return $this->deadline->isPast();
    }

    // Attribute: sisa waktu dalam format human-readable
    public function getSisaWaktuAttribute(): string
    {
        if ($this->deadline->isPast()) {
            return 'Sudah lewat';
        }
        return $this->deadline->diffForHumans(['parts' => 2]);
    }

    // Attribute: warna badge berdasarkan sisa deadline
    public function getDeadlineColorAttribute(): string
    {
        if ($this->deadline->isPast()) {
            return 'merah';
        }
        $hariSisa = now()->diffInHours($this->deadline);
        if ($hariSisa <= 24) {
            return 'oranye'; // deadline < 24 jam
        }
        if ($hariSisa <= 72) {
            return 'kuning'; // deadline < 3 hari
        }
        return 'hijau'; // masih jauh
    }
}
