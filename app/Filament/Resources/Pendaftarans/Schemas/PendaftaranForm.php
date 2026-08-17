<?php

// Lokasi folder

namespace App\Filament\Resources\Pendaftarans\Schemas;

// Elemen Form
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * PendaftaranForm
 *
 * Mengatur bentuk kotak isian saat membuat pengumuman Pendaftaran Baru.
 */
class PendaftaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Bagian 1: Informasi tulisan
                Section::make('📝 Informasi Utama Pendaftaran')
                    ->description('Isi judul dan keterangan singkat tentang pendaftaran ini.')
                    ->schema([
                        // Judul pendaftaran
                        TextInput::make('judul')
                            ->label('Judul Pendaftaran')
                            ->placeholder('Contoh: Penerimaan Peserta Didik Baru 2025/2026')
                            ->helperText('Judul ini akan tampil di halaman utama website.')
                            ->required(),

                        // Teks penjelasan panjang
                        Textarea::make('deskripsi')
                            ->label('Deskripsi / Informasi Lengkap')
                            ->placeholder('Tuliskan informasi lengkap tentang pendaftaran, seperti jadwal, syarat tambahan, dll.')
                            ->helperText('Informasi ini akan ditampilkan kepada orang tua yang ingin mendaftarkan anaknya.')
                            ->rows(6) // Ketinggian form 6 baris
                            ->columnSpanFull(),
                    ]),

                // Bagian 2: Tautan/Link Formulir (Misal Google Form)
                Section::make('🔗 Tautan Formulir Online')
                    ->description('Masukkan link Google Form atau platform pendaftaran lainnya.')
                    ->schema([
                        TextInput::make('link_pendaftaran')
                            ->label('Link / Tautan Formulir Pendaftaran')
                            ->placeholder('Contoh: https://forms.google.com/...')
                            ->helperText('Tautkan ke Google Form atau sistem pendaftaran online sekolah. Pastikan link dapat diakses publik.')
                            ->url() // Wajib diisi dengan format link website (http/https)
                            ->suffixIcon('heroicon-o-link') // Menambahkan ikon rantai kecil di ujung kanan kotak isian
                            ->required(),
                    ]),

                // Bagian 3: Tombol aktifasi
                Section::make('⚙️ Status Pendaftaran')
                    ->description('Atur apakah pendaftaran ini sedang aktif atau belum.')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Tampilkan di Website (Aktifkan Pendaftaran)')
                            ->helperText('Jika diaktifkan, informasi dan tombol pendaftaran ini akan muncul di halaman depan website.')
                            ->default(true) // Bawaan ON
                            ->onColor('success') // Warna saat ON = Hijau
                            ->offColor('danger'), // Warna saat OFF = Merah
                    ]),

            ]);
    }
}
