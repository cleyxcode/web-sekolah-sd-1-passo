<?php

namespace App\Filament\Widgets;

use App\Models\Nilai;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class NilaiChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Rata-rata Nilai Per Mata Pelajaran';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    // Widget grafik nilai: Super Admin & Kepala Sekolah (data sensitif akademik)
    public static function canView(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->hasRole('Super Admin') || $user->can('view_dashboard_chart');
    }

    protected function getData(): array
    {
        // Rata-rata nilai per mata pelajaran (join ke tabel mata_pelajarans)
        $nilais = Nilai::selectRaw('mata_pelajaran_id, AVG(nilai_angka) as rata_rata, COUNT(*) as total')
            ->with('mataPelajaran')
            ->groupBy('mata_pelajaran_id')
            ->orderByDesc('rata_rata')
            ->limit(10)
            ->get();

        $labels = $nilais->map(fn ($n) => $n->mataPelajaran?->nama ?? 'N/A')->toArray();
        $data   = $nilais->map(fn ($n) => round($n->rata_rata, 1))->toArray();

        // Warna dinamis berdasarkan nilai rata-rata
        $backgroundColors = $nilais->map(function ($n) {
            $rata = $n->rata_rata;
            if ($rata >= 85) return 'rgba(34, 197, 94, 0.7)';  // hijau
            if ($rata >= 70) return 'rgba(234, 179, 8, 0.7)';  // kuning
            return 'rgba(239, 68, 68, 0.7)';                   // merah
        })->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Rata-rata Nilai',
                    'data'            => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderRadius'    => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => false,
                    'min'         => 50,
                    'max'         => 100,
                ],
            ],
            'plugins' => [
                'legend' => ['display' => false],
            ],
        ];
    }
}
