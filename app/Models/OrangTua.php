<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class OrangTua extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];
    protected $hidden  = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ─── RELASI ────────────────────────────────────────────

    /** Semua siswa yang diasuh (many-to-many) */
    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'orang_tua_siswas')
            ->withTimestamps();
    }

    /** Siswa pertama (untuk portal orang tua yang satu anak) */
    public function siswa()
    {
        return $this->siswas()->first();
    }

    // ─── HELPERS ───────────────────────────────────────────

    /** URL foto orang tua */
    public function getFotoUrlAttribute(): ?string
    {
        if (!isset($this->foto) || !$this->foto) return null;
        return \Illuminate\Support\Facades\Storage::url($this->foto);
    }

    /** Nama anak pertama (shortcut untuk tampilan) */
    public function getNamaAnakAttribute(): string
    {
        return $this->siswas()->first()?->nama ?? '-';
    }
}
