<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;

class BalanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $balance = Transaction::query()->where('type', 'income')->sum('value') + Transaction::query()->where('type', 'expense')->sum('value');
        $transactions_chart = [];
        $transactions = Transaction::query()->get('value');
        foreach($transactions as $transaction) {
            array_push($transactions_chart, $transaction['value']);
        }

        if($balance > 0) {

            return [
                Stat::make('Wallet', 'R$'.$balance)
                    ->description(' ')
                    ->chart($transactions_chart)
                    ->descriptionIcon('heroicon-o-arrow-trending-up')
                    ->color('success'),
            ];
        }

        return [
            Stat::make('Balance', $balance)
                ->chart($transactions_chart)
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
