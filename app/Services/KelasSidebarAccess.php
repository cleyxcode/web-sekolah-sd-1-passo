<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Menentukan pintasan sidebar Kelas 1–6 berdasarkan role pengguna.
 *
 * - Super Admin & Kepala Sekolah: semua tingkat 1–6
 * - Guru (wali kelas): hanya tingkat kelas yang diwalikan
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
        return $this->accessibleTingkats($user)->isNotEmpty();
    }

    /**
     * Apakah user boleh membuka halaman tingkat tertentu.
     */
    public function canAccessTingkat(User $user, int $tingkat): bool
    {
        if (! in_array($tingkat, self::TINGKAT_SEMUA, true)) {
            return false;
        }

        return $this->accessibleTingkats($user)->contains($tingkat);
    }

    /**
     * Daftar tingkat (1–6) yang boleh muncul di sidebar untuk user ini.
     *
     * @return Collection<int, int>
     */
    public function accessibleTingkats(User $user): Collection
    {
        if ($user->hasRole(['Super Admin', 'Kepala Sekolah'])) {
            return collect(self::TINGKAT_SEMUA);
        }

        if (! $user->hasRole('Guru')) {
            return collect();
        }

        $guru = Guru::query()->where('user_id', $user->id)->first();

        if ($guru === null) {
            return collect();
        }

        return Kelas::query()
            ->where('wali_kelas_id', $guru->id)
            ->whereIn('tingkat', self::TINGKAT_SEMUA)
            ->orderBy('tingkat')
            ->pluck('tingkat')
            ->unique()
            ->values()
            ->map(fn ($tingkat): int => (int) $tingkat);
    }

    /**
     * Ambil record kelas untuk suatu tingkat, dibatasi hak akses user.
     *
     * @return Collection<int, Kelas>
     */
    public function kelasForTingkat(User $user, int $tingkat): Collection
    {
        if (! $this->canAccessTingkat($user, $tingkat)) {
            return collect();
        }

        $query = Kelas::query()
            ->with(['waliKelas', 'tahunAjaran'])
            ->withCount([
                'siswasAktif as siswas_count',
                'nilais',
                'presensis',
                'tugas',
                'jadwalPelajarans',
            ])
            ->where('tingkat', $tingkat)
            ->orderBy('nama_kelas');

        if ($user->hasRole('Guru') && ! $user->hasRole(['Super Admin', 'Kepala Sekolah'])) {
            $guru = Guru::query()->where('user_id', $user->id)->first();

            if ($guru === null) {
                return collect();
            }

            $query->where('wali_kelas_id', $guru->id);
        }

        return $query->get();
    }
}
