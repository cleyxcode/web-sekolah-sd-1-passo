<?php

namespace App\Filament\Resources\Tugas\Schemas;

use App\Models\Guru;
use App\Models\Kelas;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Tugas')
                ->description('Isi detail tugas yang akan diberikan kepada siswa.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    Grid::make(2)->schema([

                        TextInput::make('judul')
                            ->label('Judul Tugas')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Latihan Soal Matematika Bab 3')
                            ->columnSpanFull(),

                        Select::make('kelas_id')
                            ->label('Kelas Tujuan')
                            ->required()
                            ->searchable()
                            ->options(function () {
                                $user = Auth::user();

                                // Guru hanya bisa memilih kelas yang ia wali
                                if ($user?->hasRole('Guru')) {
                                    $guru = Guru::where('user_id', $user->id)->first();
                                    if (!$guru) return [];
                                    return Kelas::where('wali_kelas_id', $guru->id)
                                        ->orderBy('tingkat')
                                        ->get()
                                        ->mapWithKeys(fn($k) => [
                                            $k->id => "Kelas {$k->nama_kelas} (Tingkat {$k->tingkat})"
                                        ]);
                                }

                                // Admin/Kepala Sekolah bisa pilih semua kelas
                                return Kelas::with('waliKelas')
                                    ->orderBy('tingkat')
                                    ->orderBy('nama_kelas')
                                    ->get()
                                    ->mapWithKeys(fn($k) => [
                                        $k->id => "Kelas {$k->nama_kelas} — Wali: " . ($k->waliKelas->nama ?? 'Belum ada')
                                    ]);
                            })
                            ->helperText(fn() => Auth::user()?->hasRole('Guru')
                                ? 'Hanya menampilkan kelas yang Anda wali.'
                                : 'Pilih kelas yang akan menerima tugas ini.'),

                        Select::make('guru_id')
                            ->label('Guru Pemberi Tugas')
                            ->relationship('guru', 'nama')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(function () {
                                $user = Auth::user();
                                if ($user?->hasRole('Guru')) {
                                    return Guru::where('user_id', $user->id)->value('id');
                                }
                                return null;
                            })
                            ->helperText('Otomatis terisi jika login sebagai Guru.'),

                        TextInput::make('mata_pelajaran')
                            ->label('Mata Pelajaran')
                            ->maxLength(100)
                            ->placeholder('Contoh: Matematika, Bahasa Indonesia...')
                            ->helperText('Opsional — isi jika tugas berkaitan dengan mapel tertentu.'),

                        DateTimePicker::make('deadline')
                            ->label('Batas Waktu (Deadline)')
                            ->required()
                            ->native(false)
                            ->minDate(now())
                            ->displayFormat('d M Y, H:i')
                            ->helperText('Tugas akan tetap ditampilkan setelah deadline sebagai pengingat.'),

                        Select::make('status')
                            ->label('Status Tugas')
                            ->options([
                                'aktif'       => '🟢 Aktif',
                                'selesai'     => '✅ Selesai',
                                'dibatalkan'  => '❌ Dibatalkan',
                            ])
                            ->default('aktif')
                            ->required()
                            ->native(false),
                    ]),

                    Textarea::make('deskripsi')
                        ->label('Deskripsi / Instruksi Tugas')
                        ->rows(5)
                        ->placeholder('Tuliskan instruksi tugas secara jelas, termasuk cara pengerjaan, format pengumpulan, dll...')
                        ->helperText('Akan ditampilkan di portal orang tua.')
                        ->columnSpanFull(),
                ]),

            Section::make('Lampiran Tugas')
                ->description('Upload foto atau file dokumen pendukung tugas (opsional).')
                ->icon('heroicon-o-paper-clip')
                ->collapsed()
                ->schema([
                    Grid::make(2)->schema([
                        FileUpload::make('foto_tugas')
                            ->label('Foto Tugas')
                            ->directory('tugas/foto')
                            ->image()
                            ->multiple()
                            ->maxFiles(5)
                            ->maxSize(5120) // 5MB
                            ->helperText('Upload foto (Maks 5 file, per file maks 5MB).'),

                        FileUpload::make('file_tugas')
                            ->label('Dokumen Tugas')
                            ->directory('tugas/dokumen')
                            ->acceptedFileTypes(['application/pdf', 'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->multiple()
                            ->maxFiles(5)
                            ->maxSize(5120) // 5MB
                            ->helperText('Format: PDF, Word (Maks 5 file, per file maks 5MB).'),
                    ])
                ]),

            Section::make('Komentar / Catatan Guru')
                ->description('Tambahkan komentar terkait tugas ini. Komentar akan terlihat oleh orang tua secara realtime.')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->collapsed()
                ->schema([
                    Repeater::make('komentars')
                        ->relationship('komentars')
                        ->label('Daftar Komentar')
                        ->addActionLabel('Tambah Komentar')
                        ->schema([
                            Textarea::make('komentar')
                                ->label('Pesan Komentar')
                                ->required()
                                ->rows(3)
                                ->placeholder('Contoh: "Mohon untuk siswa yang belum mengumpulkan segera diselesaikan..."'),
                            
                            Hidden::make('guru_id')
                                ->default(function () {
                                    $user = Auth::user();
                                    if ($user?->hasRole('Guru')) {
                                        return Guru::where('user_id', $user->id)->value('id');
                                    }
                                    return null;
                                })
                        ])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => $state['komentar'] ?? null)
                        ->collapsible()
                        ->cloneable(),
                ]),
        ]);
    }
}
