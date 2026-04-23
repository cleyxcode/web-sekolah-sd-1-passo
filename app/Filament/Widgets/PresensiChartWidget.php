<?php

namespace App\Filament\Widgets;

use App\Models\Presensi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PresensiChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Kehadiran Siswa (30 Hari Terakhir)';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    // Hanya role yang diizinkan yang bisa melihat widget ini
    public static function canView(): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah'])
            || $user->can('view_dashboard_stats');
    }

    protected function getData(): array
    {
        // Ambil data 30 hari terakhir
        $data = Presensi::selectRaw("status, DATE(tanggal) as tgl, COUNT(*) as total")
            ->where('tanggal', '>=', now()->subDays(29))
            ->groupByRaw("status, DATE(tanggal)")
            ->orderByRaw("DATE(tanggal) ASC")
            ->get();

        // Buat array tanggal 30 hari terakhir
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        $labels = $dates->map(fn ($d) => \Illuminate\Support\Carbon::parse($d)->format('d/m'))->toArray();

        // Susun data per status
        $statuses = ['hadir', 'sakit', 'izin', 'alpha'];
        $colors   = [
            'hadir' => 'rgb(34, 197, 94)',   // green
            'sakit' => 'rgb(234, 179, 8)',    // yellow
            'izin'  => 'rgb(99, 102, 241)',   // indigo
            'alpha' => 'rgb(239, 68, 68)',    // red
        ];

        $datasets = [];
        foreach ($statuses as $status) {
            $chartData = $dates->map(function ($date) use ($data, $status) {
                $found = $data->first(fn ($item) => $item->tgl === $date && $item->status === $status);
                return $found ? $found->total : 0;
            })->toArray();

            $datasets[] = [
                'label'           => ucfirst($status),
                'data'            => $chartData,
                'borderColor'     => $colors[$status],
                'backgroundColor' => str_replace('rgb', 'rgba', $colors[$status]) . str_replace(')', ', 0.1)', ''),
                'fill'            => true,
                'tension'         => 0.4,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels'   => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
            'plugins' => [
                'legend' => ['position' => 'bottom'],
            ],
        ];
    }
}
