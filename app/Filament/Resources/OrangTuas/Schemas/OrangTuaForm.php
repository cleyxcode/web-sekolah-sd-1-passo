<?php

namespace App\Filament\Resources\OrangTuas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class OrangTuaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                TextInput::make('no_telepon')
                    ->tel(),
                TextInput::make('pekerjaan'),
                Select::make('siswas')
                    ->multiple()
                    ->relationship('siswas', 'nama')
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('nis')->required(),
                        TextInput::make('nama')->required(),
                        Select::make('jenis_kelamin')->options(['L' => 'L', 'P' => 'P'])->required(),
                        Select::make('kelas_id')->relationship('kelas', 'nama_kelas')->required(),
                        Select::make('tahun_ajaran_id')->relationship('tahunAjaran', 'nama')->required(),
                    ])
                    ->columnSpanFull(),
                Textarea::make('alamat')
                    ->columnSpanFull(),
            ]);
    }
}
