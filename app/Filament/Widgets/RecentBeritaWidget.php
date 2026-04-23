<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Berita;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Auth;

class RecentBeritaWidget extends TableWidget
{
    protected static ?string $heading = 'Berita Terbaru';
    protected static ?int $sort = 5;

    // Semua role bisa lihat berita terbaru
    public static function canView(): bool
    {
        $user = Auth::user();
        return $user?->hasAnyRole(['Super Admin', 'Kepala Sekolah', 'Guru']) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Berita::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Berita')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        default     => 'gray',
                    }),
                TextColumn::make('published_at')
                    ->label('Tanggal Publish')
                    ->dateTime('d M Y, H:i')
                    ->default('-'),
            ])
            ->paginated(false);
    }
}
