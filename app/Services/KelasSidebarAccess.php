<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Menentukan pintasan sidebar per rombongan belajar (nama_kelas).
 *
 * - Super Admin & Kepala Sekolah: semua kelas yang ada di sistem
 * - Guru (wali kelas): hanya kelas yang diwalikan
 * - Role lain: tidak ada akses
 */
final class KelasSidebarAccess
{
    /** @var list<int> */
    public const TINGKAT_SEMUA = [1, 2, 3, 4, 5, 6];

    /**
     * Apakah user boleh melihat menu pintasan kelas sama sekali.
     */
    public function canAccessSidebar(User $user): bool
    {
        return $this->accessibleKelas($user)->isNotEmpty();
    }

    /**
     * Apakah user boleh membuka halaman rombongan belajar tertentu.
     */
    public function canAccessKelas(User $user, Kelas|int $kelas): bool
    {
        $kelasId = $kelas instanceof Kelas ? $kelas->id : $kelas;

        return $this->accessibleKelas($user)->contains('id', $kelasId);
    }

    /**
     * Daftar rombongan belajar yang boleh muncul di sidebar.
     *
     * @return Collection<int, Kelas>
     */
    public function accessibleKelas(User $user): Collection
    {
        return $this->baseKelasQuery($user)->get();
    }

    /**
     * Query dasar kelas sesuai hak akses user.
     *
     * @return Builder<Kelas>
     */
    public function baseKelasQuery(User $user): Builder
    {
        $query = Kelas::query()
            ->with(['waliKelas', 'tahunAjaran'])
            ->withCount('siswasAktif as siswas_count')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas');

        if ($user->hasRole(['Super Admin', 'Kepala Sekolah'])) {
            return $query;
        }

        if ($user->hasRole('Guru')) {
            $guru = Guru::query()->where('user_id', $user->id)->first();

            if ($guru === null) {
                return $query->whereRaw('0 = 1');
            }

            return $query->where('wali_kelas_id', $guru->id);
        }

        return $query->whereRaw('0 = 1');
    }

    /**
     * Ambil satu kelas beserta relasi & counter, jika user punya akses.
     */
    public function resolveKelas(User $user, Kelas|int $kelas): ?Kelas
    {
        $kelasId = $kelas instanceof Kelas ? $kelas->id : $kelas;

        /** @var Kelas|null $record */
        $record = $this->baseKelasQuery($user)
            ->whereKey($kelasId)
            ->withCount([
                'siswasAktif as siswas_count',
                'nilais',
                'presensis',
                'tugas',
                'jadwalPelajarans',
            ])
            ->first();

        return $record;
    }
}
