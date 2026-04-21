<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Berita;
use Filament\Tables\Columns\TextColumn;

class RecentBeritaWidget extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Berita::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('judul'),
                TextColumn::make('status'),
                TextColumn::make('published_at')->dateTime(),
            ]);
    }
}
