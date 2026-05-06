<?php

// Alamat folder tempat file ini berada
namespace App\Filament\Resources\Tugas\Schemas;

// Mengimpor model database
use App\Models\Guru;
use App\Models\Kelas;
// Mengimpor elemen-elemen untuk menyusun form
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

/**
 * TugasForm
 * 
 * Kelas ini mengatur susunan kolom isian (Form) saat menambahkan atau mengedit Tugas.
 */
class TugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Membuat sebuah Kotak Bagian (Section) yang membungkus informasi dasar tugas
            Section::make('Informasi Tugas')
                ->description('Isi detail tugas yang akan diberikan kepada siswa.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    // Membuat Grid 2 Kolom agar isian bersebelahan (kiri-kanan)
                    Grid::make(2)->schema([

                        // Isian untuk Judul Tugas
                        TextInput::make('judul')
                            ->label('Judul Tugas')
                            ->required() // Wajib diisi
                            ->maxLength(255)
                            ->placeholder('Contoh: Latihan Soal Matematika Bab 3')
                            ->columnSpanFull(), // Judul dibuat lebar penuh, memenuhi 2 kolom sekaligus

                        // Pilihan Drop-down untuk memilih kelas mana yang diberi tugas
                        Select::make('kelas_id')
                            ->label('Kelas Tujuan')
                            ->required()
                            ->searchable() // Bisa dicari dengan mengetik
                            // Menentukan apa saja pilihan kelas yang muncul
                            ->options(function () {
                                $user = Auth::user(); // Ambil data user yang login

                                // JIKA YANG LOGIN ADALAH GURU:
                                if ($user?->hasRole('Guru')) {
                                    $guru = Guru::where('user_id', $user->id)->first();
                                    if (!$guru) return []; // Kalau error, kembalikan kosong
                                    
                                    // Hanya tampilkan kelas yang dia ajar / wali-kan
                                    return Kelas::where('wali_kelas_id', $guru->id)
                                        ->orderBy('tingkat')
                                        ->get()
                                        ->mapWithKeys(fn($k) => [
                                            $k->id => "Kelas {$k->nama_kelas} (Tingkat {$k->tingkat})"
                                        ]);
                                }

                                // JIKA YANG LOGIN ADMIN / KEPALA SEKOLAH:
                                // Tampilkan SEMUA kelas yang ada di sekolah
                                return Kelas::with('waliKelas')
                                    ->orderBy('tingkat')
                                    ->orderBy('nama_kelas')
                                    ->get()
                                    ->mapWithKeys(fn($k) => [
                                        $k->id => "Kelas {$k->nama_kelas} — Wali: " . ($k->waliKelas->nama ?? 'Belum ada')
                                    ]);
                            })
                            // Teks petunjuk kecil di bawah kotak pilihan
                            ->helperText(fn() => Auth::user()?->hasRole('Guru')
                                ? 'Hanya menampilkan kelas yang Anda wali.'
                                : 'Pilih kelas yang akan menerima tugas ini.'),

                        // Pilihan Drop-down untuk memilih guru pembuat tugas
                        Select::make('guru_id')
                            ->label('Guru Pemberi Tugas')
                            ->relationship('guru', 'nama') // Ambil otomatis dari relasi tabel guru (nama)
                            ->required()
                            ->searchable()
                            ->preload()
                            // Secara otomatis langsung terpilih sesuai dengan guru yang sedang login
                            ->default(function () {
                                $user = Auth::user();
                                if ($user?->hasRole('Guru')) {
                                    return Guru::where('user_id', $user->id)->value('id');
                                }
                                return null;
                            })
                            ->helperText('Otomatis terisi jika login sebagai Guru.'),

                        // Isian untuk nama mata pelajaran (sebagai informasi pelengkap)
                        TextInput::make('mata_pelajaran')
                            ->label('Mata Pelajaran')
                            ->maxLength(100)
                            ->placeholder('Contoh: Matematika, Bahasa Indonesia...')
                            ->helperText('Opsional — isi jika tugas berkaitan dengan mapel tertentu.'),

                        // Isian pemilih Tanggal & Waktu kapan batas waktu tugas berakhir
                        DateTimePicker::make('deadline')
                            ->label('Batas Waktu (Deadline)')
                            ->required()
                            ->native(false) // Gunakan pop-up kalender bawaan Filament yang lebih bagus (bukan dari browser)
                            ->minDate(now()) // Tidak boleh pilih tanggal di masa lalu (kemarin)
                            ->displayFormat('d M Y, H:i')
                            ->helperText('Tugas akan tetap ditampilkan setelah deadline sebagai pengingat.'),

                        // Isian Drop-down untuk status tugas
                        Select::make('status')
                            ->label('Status Tugas')
                            ->options([
                                'aktif'       => '🟢 Aktif',
                                'selesai'     => '✅ Selesai',
                                'dibatalkan'  => '❌ Dibatalkan',
                            ])
                            ->default('aktif') // Status bawaan saat pertama bikin adalah "Aktif"
                            ->required()
                            ->native(false),
                    ]),

                    // Isian berupa kotak teks besar (Textarea) untuk penjelasan tugas
                    Textarea::make('deskripsi')
                        ->label('Deskripsi / Instruksi Tugas')
                        ->rows(5) // Tingginya sebanyak 5 baris
                        ->placeholder('Tuliskan instruksi tugas secara jelas, termasuk cara pengerjaan, format pengumpulan, dll...')
                        ->helperText('Akan ditampilkan di portal orang tua.')
                        ->columnSpanFull(), // Lebar penuh
                ]),

            // Membuat Kotak Bagian (Section) kedua khusus untuk lampiran file
            Section::make('Lampiran Tugas')
                ->description('Upload foto atau file dokumen pendukung tugas (opsional).')
                ->icon('heroicon-o-paper-clip')
                ->collapsed() // Secara otomatis kotak ini tertutup (bisa di-klik untuk membuka), agar tampilan tidak terlalu panjang
                ->schema([
                    Grid::make(2)->schema([
                        // Isian upload Foto pendukung
                        FileUpload::make('foto_tugas')
                            ->label('Foto Tugas')
                            ->directory('tugas/foto') // Simpan ke folder public/storage/tugas/foto
                            ->image()                 // Hanya menerima file bertipe gambar (JPG, PNG)
                            ->multiple()              // Boleh upload lebih dari 1 file
                            ->maxFiles(5)             // Tapi dibatasi maksimal hanya 5 file
                            ->maxSize(5120)           // Ukuran maksimal 1 file adalah 5 MB (5120 KB)
                            ->helperText('Upload foto (Maks 5 file, per file maks 5MB).'),

                        // Isian upload File dokumen (PDF, Word)
                        FileUpload::make('file_tugas')
                            ->label('Dokumen Tugas')
                            ->directory('tugas/dokumen')
                            // Hanya menerima file PDF atau Microsoft Word
                            ->acceptedFileTypes(['application/pdf', 'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->multiple()
                            ->maxFiles(5)
                            ->maxSize(5120) // 5MB
                            ->helperText('Format: PDF, Word (Maks 5 file, per file maks 5MB).'),
                    ])
                ]),

            // Membuat Kotak Bagian (Section) ketiga untuk menambah pesan komentar
            Section::make('Komentar / Catatan Guru')
                ->description('Tambahkan komentar terkait tugas ini. Komentar akan terlihat oleh orang tua secara realtime.')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->collapsed() // Ditutup secara bawaan
                ->schema([
                    // Repeater = Elemen yang bisa diulang-ulang. 
                    // Digunakan untuk menambahkan banyak komentar sekaligus.
                    Repeater::make('komentars')
                        ->relationship('komentars') // Tersambung ke tabel anak 'komentar_tugas'
                        ->label('Daftar Komentar')
                        ->addActionLabel('Tambah Komentar') // Tulisan pada tombol tambah
                        ->schema([
                            // Isi tiap-tiap komentar
                            Textarea::make('komentar')
                                ->label('Pesan Komentar')
                                ->required()
                                ->rows(3)
                                ->placeholder('Contoh: "Mohon untuk siswa yang belum mengumpulkan segera diselesaikan..."'),
                            
                            // Kotak isian tersembunyi (Hidden) untuk menyimpan ID siapa guru yang komentar
                            Hidden::make('guru_id')
                                ->default(function () {
                                    $user = Auth::user();
                                    if ($user?->hasRole('Guru')) {
                                        return Guru::where('user_id', $user->id)->value('id');
                                    }
                                    return null;
                                })
                        ])
                        ->defaultItems(0) // Awal mula kotak ini kosong (0 kotak form komentar)
                        ->itemLabel(fn (array $state): ?string => $state['komentar'] ?? null) // Tampilkan tulisan komentar sebagai judul saat ditutup
                        ->collapsible() // Boleh dibuka-tutup
                        ->cloneable(),  // Tombol untuk menduplikasi / menyalin kolom
                ]),
        ]);
    }
}
