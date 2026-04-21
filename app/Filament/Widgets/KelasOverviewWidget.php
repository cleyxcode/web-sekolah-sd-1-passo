<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Kelas;
use Filament\Tables\Columns\TextColumn;

class KelasOverviewWidget extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Kelas::query()->withCount('siswas'))
            ->columns([
                TextColumn::make('nama_kelas')->label('Nama Kelas'),
                TextColumn::make('tingkat')->label('Tingkat'),
                TextColumn::make('siswas_count')->label('Jumlah Siswa'),
            ]);
    }
}
