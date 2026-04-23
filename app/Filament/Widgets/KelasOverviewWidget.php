<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Kelas;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class KelasOverviewWidget extends TableWidget
{
    protected static ?string $heading = 'Ringkasan Data Kelas';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    // Semua role bisa lihat ringkasan kelas
    public static function canView(): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        return $user->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Guru'])
            || $user->can('view_dashboard_stats');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Kelas::query()
                ->with(['waliKelas', 'tahunAjaran'])
                ->withCount('siswas')
            )
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('waliKelas.nama')
                    ->label('Wali Kelas')
                    ->default('-'),
                TextColumn::make('tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->default('-'),
                TextColumn::make('siswas_count')
                    ->label('Jumlah Siswa')
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 30 => 'success',
                        $state >= 20 => 'warning',
                        default      => 'danger',
                    }),
            ])
            ->paginated(false);
    }
}
