<?php

namespace App\Filament\Resources\SettingSekolahs\Pages;

use App\Filament\Resources\SettingSekolahs\SettingSekolahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSettingSekolah extends EditRecord
{
    protected static string $resource = SettingSekolahResource::class;

    public function mount(int | string $record = null): void
    {
        $setting = \App\Models\SettingSekolah::firstOrCreate(['id' => 1]);
        parent::mount($setting->id);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
