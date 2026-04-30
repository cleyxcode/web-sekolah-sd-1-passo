<?php

namespace App\Filament\Resources\Nilais\Pages;

use App\Filament\Resources\Nilais\NilaiResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListNilais extends ListRecords
{
    protected static string $resource = NilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Nilai'),

            Action::make('rekap_kelas')
                ->label('📊 Rekap Nilai Kelas')
                ->color('info')
                ->icon('heroicon-o-table-cells')
                ->url(NilaiResource::getUrl('rekap-kelas')),
        ];
    }
}
