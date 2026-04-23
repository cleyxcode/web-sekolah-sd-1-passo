<?php

namespace App\Filament\Widgets;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\OrangTua;
use App\Models\Pendaftaran;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    // Hanya role yang diizinkan yang bisa melihat widget ini
    public static function canView(): bool
    {
        $user = Auth::user();

        if (!$user) return false;

        // Super Admin selalu bisa lihat
        if ($user->hasRole('Super Admin')) return true;

        // Kepala Sekolah bisa lihat
        if ($user->hasRole('Kepala Sekolah')) return true;

        // Guru hanya jika punya permission khusus
        return $user->can('view_dashboard_stats');
    }

    protected function getStats(): array
    {
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();
        $totalGuru       = Guru::count();
        $totalOrangTua   = OrangTua::count();
        $pendaftaranBaru = Pendaftaran::whereMonth('created_at', now()->month)->count();

        // Tren siswa bulan ini vs bulan lalu
        $siswaBulanIni  = Siswa::whereMonth('created_at', now()->month)->count();
        $siswaBulanLalu = Siswa::whereMonth('created_at', now()->subMonth()->month)->count();
        $trenSiswa = $siswaBulanIni >= $siswaBulanLalu ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $colorSiswa = $siswaBulanIni >= $siswaBulanLalu ? 'success' : 'danger';

        return [
            Stat::make('Total Siswa Aktif', $totalSiswaAktif)
                ->description('Siswa aktif saat ini')
                ->descriptionIcon($trenSiswa)
                ->color($colorSiswa)
                ->chart(
                    Siswa::selectRaw('COUNT(*) as count')
                        ->where('status', 'aktif')
                        ->whereDate('created_at', '>=', now()->subDays(6))
                        ->groupByRaw('DATE(created_at)')
                        ->pluck('count')
                        ->toArray() ?: [0]
                ),

            Stat::make('Total Guru', $totalGuru)
                ->description('Guru terdaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),

            Stat::make('Total Orang Tua', $totalOrangTua)
                ->description('Akun orang tua/wali terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),

            Stat::make('Pendaftaran Bulan Ini', $pendaftaranBaru)
                ->description('Calon siswa baru bulan ini')
                ->descriptionIcon('heroicon-m-document-plus')
                ->color('primary'),
        ];
    }
}
