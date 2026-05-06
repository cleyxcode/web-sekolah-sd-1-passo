<?php

// Alamat folder
namespace App\Filament\Resources\Siswas\Schemas;

// Impor komponen form
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

/**
 * SiswaForm
 * 
 * Mengatur tampilan dan perilaku form pengisian data siswa
 */
class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                // Kotak Nomor Induk Siswa
                TextInput::make('nis')
                    ->required(), // Wajib
                
                // Kotak Nama Siswa
                TextInput::make('nama')
                    ->required(), // Wajib
                
                // Drop-down Jenis Kelamin (L / P)
                Select::make('jenis_kelamin')
                    ->options(['L' => 'Laki-Laki', 'P' => 'Perempuan']) // Ditulis lebih jelas
                    ->required(),
                
                // Kalender Tanggal Lahir
                DatePicker::make('tanggal_lahir'),
                
                // Kotak teks besar untuk Alamat
                Textarea::make('alamat')
                    ->columnSpanFull(), // Lebar penuh
                
                // Kotak untuk link/path foto (mungkin bisa diubah jadi FileUpload nanti)
                TextInput::make('foto'),
                
                // Drop-down Pilihan Kelas
                Select::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas') // Ambil otomatis dari tabel kelas
                    ->searchable() // Bisa dicari dengan mengetik
                    ->preload(),   // Dimuat di awal
                
                // Drop-down Tahun Ajaran
                Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'nama')
                    ->searchable()
                    ->preload(),
                
                // Drop-down Status Siswa
                Select::make('status')
                    ->options([
                        'aktif' => 'Aktif', 
                        'lulus' => 'Lulus', 
                        'pindah' => 'Pindah'
                    ])
                    ->default('aktif') // Bawaannya adalah "aktif"
                    ->required(),
                
                // Drop-down Memilih Orang Tua
                // Ini penting: satu siswa bisa dikaitkan ke orang tua yang sudah ada
                Select::make('orangTuas')
                    ->multiple() // Bisa pilih ayah dan ibu sekaligus
                    ->relationship('orangTuas', 'nama') // Diambil dari tabel orang_tuas
                    ->preload()
                    ->searchable()
                    ->label('Pilih Orang Tua (Jika Kakaknya Sudah Sekolah Disini)')
                    ->helperText('Biarkan kosong. Sistem akan otomatis membuatkan akun baru jika tidak dipilih.')
                    ->columnSpanFull(), // Lebar penuh
            ]);
    }
}
