<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PresensiChartWidget extends ChartWidget
{
    protected ?string $heading = 'Presensi Chart Widget';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
