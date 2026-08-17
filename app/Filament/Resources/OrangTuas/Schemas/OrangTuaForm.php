<?php

// Lokasi folder

namespace App\Filament\Resources\OrangTuas\Schemas;

// Elemen Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

 // Untuk mengenkripsi password

/**
 * OrangTuaForm
 *
 * Mengatur susunan kotak isian untuk membuat akun Portal Orang Tua.
 */
class OrangTuaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Bagian 1: Email dan Password Login
                Section::make('Informasi Akun')
                    ->description('Kredensial login untuk akun orang tua.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        // Email (dipakai untuk login)
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->unique(ignoreRecord: true) // Email tidak boleh sama dengan orang tua lain (unik)
                            ->required(),

                        Grid::make(2)->schema([
                            // Kotak Kata Sandi
                            TextInput::make('password')
                                ->password()
                                ->label('Kata Sandi')
                                ->revealable() // Munculkan tombol mata untuk melihat password
                                // Enkripsi (acak) password sebelum disimpan ke database
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                // Jangan simpan password jika kotak ini dikosongkan (saat edit)
                                ->dehydrated(fn ($state) => filled($state))
                                // Wajib diisi HANYA SAAT pertama kali membuat akun
                                ->required(fn (string $context): bool => $context === 'create')
                                // Wajib SAMA persis isinya dengan kotak 'password_confirmation' di bawah
                                ->same('password_confirmation')
                                ->helperText(fn (string $context): string => $context === 'edit' ? 'Biarkan kosong jika tidak ingin mengubah kata sandi.' : 'Tentukan kata sandi untuk akun orang tua.'),

                            // Kotak Ulangi Kata Sandi
                            TextInput::make('password_confirmation')
                                ->password()
                                ->label('Konfirmasi Kata Sandi')
                                ->revealable()
                                // Kotak ini wajib diisi KALAU kotak password diisi
                                ->requiredWith('password')
                                // Nilai kotak ini TIDAK perlu disimpan ke database (hanya untuk cek saja)
                                ->dehydrated(false)
                                ->helperText(fn (string $context): string => $context === 'edit' ? 'Ulangi kata sandi baru jika mengubahnya.' : 'Ulangi kata sandi untuk konfirmasi.'),
                        ]),
                    ]),

                // Bagian 2: Data Diri Orang Tua
                Section::make('Profil Orang Tua')
                    ->description('Data profil orang tua / wali murid.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required(),
                        Grid::make(3)->schema([
                            TextInput::make('no_telepon')
                                ->label('No. Telepon / WhatsApp')
                                ->tel(),
                            TextInput::make('pekerjaan')
                                ->label('Pekerjaan'),
                            Select::make('hubungan')
                                ->label('Hubungan dengan Siswa')
                                ->options([
                                    'Ayah' => 'Ayah',
                                    'Ibu' => 'Ibu',
                                    'Wali' => 'Wali',
                                ])
                                ->native(false),
                        ]),
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                    ]),

                // Bagian 3: Menyambungkan ke Data Anaknya
                Section::make('Data Anak (Siswa)')
                    ->description('Hubungkan akun orang tua dengan data siswa.')
                    ->icon('heroicon-o-users')
                    ->schema([
                        // Drop-down pilihan anak
                        Select::make('siswas')
                            ->label('Pilih Siswa (Anak)')
                            ->multiple() // Satu orang tua bisa pilih BANYAK anak
                            ->relationship(
                                name: 'siswas', // Sambung ke tabel siswa melalui tabel perantara (pivot) orang_tua_siswa
                                titleAttribute: 'nama', // Tampilkan namanya
                                modifyQueryUsing: fn (Builder $query) => $query->with('kelas')
                            )
                            // Menyusun nama pilihan agar lebih detail (Contoh: "Budi - Kelas: 1A")
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama} ".($record->kelas ? "- Kelas: {$record->kelas->nama_kelas}" : '- Belum Ada Kelas'))
                            ->preload()
                            ->searchable(['nama', 'nis']) // Bisa dicari berdasarkan nama atau NIS

                            // FITUR SPESIAL: Bisa buat data anak baru langsung dari dalam form orang tua!
                            ->createOptionForm([
                                TextInput::make('nis')->required(),
                                TextInput::make('nama')->required(),
                                Select::make('jenis_kelamin')->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])->required(),
                                Select::make('kelas_id')->relationship('kelas', 'nama_kelas')->required(),
                                Select::make('tahun_ajaran_id')->relationship('tahunAjaran', 'nama')->required(),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
