<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use App\Models\Category;

class CategoryExpensesChart extends ChartWidget
{
    protected static ?string $heading = 'Category Expenses';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $expenses = Transaction::query()->selectRaw('category_id as category, SUM(value) as value')
                                        ->where(['user_id' => auth()->id(), 'type' => 'expense'])
                                        ->groupBy('category')->get();

        $category_list = [];

        for($i = 0; $i < count($expenses); $i++) {
            array_push($category_list, Category::query()->find($expenses[$i]->category)->name);
        }


        return [
            'datasets' => [
                [
                    'data' => $expenses->pluck('value')->toArray(),
                    // 'backgroundColor' => ['#FF6633', '#FFB399', '#FF33FF'],
                    // 'borderColor' => ['#FF6633', '#FFB399', '#FF33FF'],
                ],
            ],
            'labels' => $category_list,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
