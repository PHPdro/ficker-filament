<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;

class TransactionOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $incomes = Transaction::query()->selectRaw('MONTH(date) as month, count(*) as total')
                                        ->where(['user_id' => auth()->id(), 'type' => 'income'])
                                        ->groupBy('month')->get();
        
        $incomes_chart = [];                   
        foreach($incomes as $income) {
            array_push($incomes_chart, $income['total']);
        }

        $expenses = Transaction::query()->selectRaw('MONTH(date) as month, count(*) as total')
                                        ->where(['user_id' => auth()->id(), 'type' => 'expense'])
                                        ->groupBy('month')->get();
        
        $expenses_chart = [];                   
        foreach($expenses as $expense) {
            array_push($expenses_chart, $expense['total']);
        }

        return [
            Stat::make('Incomes', Transaction::query()
                                    ->where(['user_id' => auth()->id(), 'type' => 'income'])
                                    ->count())
                ->description('This month: '. Transaction::query()
                                                ->whereMonth('date', '=', today()->month)
                                                ->where(['user_id' => auth()->id(), 'type' => 'income'])
                                                ->count())
                ->chart($incomes_chart)
                ->color('primary'),


            Stat::make('Expenses', Transaction::query()
                                    ->where(['user_id' => auth()->id(), 'type' => 'expense'])
                                    ->count())
                ->description('This month: '. Transaction::query()
                                                ->whereMonth('date', '=', today()->month)
                                                ->where(['user_id' => auth()->id(), 'type' => 'expense'])
                                                ->count())
                ->chart($expenses_chart)
                ->color('primary'),
        ];
    }   
}
