<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class NilaiChartWidget extends ChartWidget
{
    protected ?string $heading = 'Nilai Chart Widget';

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
