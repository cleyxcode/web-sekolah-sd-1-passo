<?php

namespace App\Filament\Resources\OrangTuas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class OrangTuaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->description('Kredensial login untuk akun orang tua.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Grid::make(2)->schema([
                            TextInput::make('password')
                                ->password()
                                ->label('Kata Sandi')
                                ->revealable()
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn (string $context): bool => $context === 'create')
                                ->same('password_confirmation')
                                ->helperText(fn (string $context): string => $context === 'edit' ? 'Biarkan kosong jika tidak ingin mengubah kata sandi.' : 'Tentukan kata sandi untuk akun orang tua.'),
                            TextInput::make('password_confirmation')
                                ->password()
                                ->label('Konfirmasi Kata Sandi')
                                ->revealable()
                                ->requiredWith('password')
                                ->dehydrated(false)
                                ->helperText(fn (string $context): string => $context === 'edit' ? 'Ulangi kata sandi baru jika mengubahnya.' : 'Ulangi kata sandi untuk konfirmasi.'),
                        ]),
                    ]),

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
                                    'Ibu'  => 'Ibu',
                                    'Wali' => 'Wali',
                                ])
                                ->native(false),
                        ]),
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                    ]),

                Section::make('Data Anak (Siswa)')
                    ->description('Hubungkan akun orang tua dengan data siswa.')
                    ->icon('heroicon-o-users')
                    ->schema([
                        Select::make('siswas')
                            ->label('Pilih Siswa (Anak)')
                            ->multiple()
                            ->relationship(
                                name: 'siswas', 
                                titleAttribute: 'nama',
                                modifyQueryUsing: fn (\Illuminate\Database\Eloquent\Builder $query) => $query->with('kelas')
                            )
                            ->getOptionLabelFromRecordUsing(fn (\Illuminate\Database\Eloquent\Model $record) => "{$record->nama} " . ($record->kelas ? "- Kelas: {$record->kelas->nama_kelas}" : '- Belum Ada Kelas'))
                            ->preload()
                            ->searchable(['nama', 'nis'])
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
