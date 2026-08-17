<?php

namespace App\Filament\Resources\SettingSekolahs\Pages;

use App\Filament\Resources\SettingSekolahs\SettingSekolahResource;
use App\Models\SettingSekolah;
use Filament\Resources\Pages\EditRecord;

class EditSettingSekolah extends EditRecord
{
    protected static string $resource = SettingSekolahResource::class;

    public function mount(int|string|null $record = null): void
    {
        $setting = SettingSekolah::firstOrCreate(['id' => 1]);
        parent::mount($setting->id);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
