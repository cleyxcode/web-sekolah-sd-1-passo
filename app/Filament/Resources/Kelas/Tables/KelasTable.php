<?php

// Lokasi folder

namespace App\Filament\Resources\Kelas\Tables;

// Tombol & Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * KelasTable
 *
 * Mengatur kolom daftar kelas, termasuk bisa menghitung otomatis
 * ada berapa siswa di dalam masing-masing kelas tersebut.
 */
class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Menampilkan angka tingkat kelas dalam bentuk lencana (badge)
                TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->formatStateUsing(fn ($state) => 'Kelas '.$state) // Mengubah tampilan dari '1' jadi 'Kelas 1'
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                // Menampilkan nama lengkap kelasnya
                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'), // Huruf tebal

                // Menampilkan nama wali kelas beserta ikon orang
                TextColumn::make('waliKelas.nama')
                    ->label('Wali Kelas')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum ditentukan') // Teks jika wali kelas masih kosong
                    ->icon('heroicon-o-user'), // Tambahkan ikon orang di sebelah nama

                // Menampilkan tahun ajaran (misal: 2024/2025 Genap)
                TextColumn::make('tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                // FITUR PINTAR: Menghitung total murid di kelas tersebut
                TextColumn::make('siswas_count')
                    ->label('Jumlah Siswa')
                    ->counts('siswas') // Otomatis menghitung dari relasi tabel siswa
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // Tabel otomatis diurutkan dari Kelas 1 sampai Kelas 6
            ->defaultSort('tingkat')

            // Filter berdasarkan Tingkat Kelas
            ->filters([
                SelectFilter::make('tingkat')
                    ->label('Tingkat Kelas')
                    ->options([
                        1 => 'Kelas 1',
                        2 => 'Kelas 2',
                        3 => 'Kelas 3',
                        4 => 'Kelas 4',
                        5 => 'Kelas 5',
                        6 => 'Kelas 6',
                    ]),

                // Filter berdasarkan Tahun Ajaran
                SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'nama'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
