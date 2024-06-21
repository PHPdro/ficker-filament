<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class CategoryExpensesChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [1, 10, 5],
                    'backgroundColor' => ['#FF6633', '#FFB399', '#FF33FF'],
                    'borderColor' => ['#FF6633', '#FFB399', '#FF33FF'],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
