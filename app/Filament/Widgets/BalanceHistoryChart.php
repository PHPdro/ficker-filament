<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use DateTime;
use IntlDateFormatter;
use App\Models\Balance;

class BalanceHistoryChart extends ChartWidget
{
    protected static ?string $heading = 'Balance History';

    public ?string $filter = '2024';

    protected int | string | array $columnSpan = '2';
    protected static ?string $maxHeight = '300px';


    public function getDescription(): ?string
    {
        return 'The balance of every month throughout the selected year.';
    }

    protected function getData(): array
    {   
        $activeFilter = $this->filter;

        $balances = Balance::selectRaw('MONTH(date) as month, SUM(amount) as amount')
                            ->whereYear('date', $activeFilter)
                            ->where('user_id', auth()->id())
                            ->groupBy('month')
                            ->orderBy('month')->get();

        $incomes = Transaction::query()->selectRaw('MONTH(date) as month, SUM(value) as value')
                                        ->whereYear('date', $activeFilter)
                                        ->where(['user_id' => auth()->id(), 'type' => 'income'])
                                        ->groupBy('month')->get();

        $expenses = Transaction::query()->selectRaw('MONTH(date) as month, SUM(value) as value')
                                        ->whereYear('date', $activeFilter)
                                        ->where(['user_id' => auth()->id(), 'type' => 'expense'])
                                        ->groupBy('month')->get();

        $months = [];
        $months_balance = [0,0,0,0,0,0,0,0,0,0,0,0];
        $months_incomes = [0,0,0,0,0,0,0,0,0,0,0,0];
        $months_expenses = [0,0,0,0,0,0,0,0,0,0,0,0];

        for ($i = 0; $i < count($balances); $i++) {
            $months_balance[$balances[$i]->month - 1] = $balances[$i]->amount;
        }

        for ($i = 0; $i < count($incomes); $i++) {
            $months_incomes[$incomes[$i]->month - 1] = $incomes[$i]->value;
        }

        for ($i = 0; $i < count($expenses); $i++) {
            $months_expenses[$expenses[$i]->month - 1] = $expenses[$i]->value;
        }

        for ($i = 0; $i < count($months_balance); $i++) {
            //Convertendo o inteiro para o nome do mês correspondente e adicionando ao array months
            $month = DateTime::createFromFormat('!m', array_keys($months_balance)[$i]+1);
            $formatter = new IntlDateFormatter('en-US', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMM');
            array_push($months, $formatter->format($month));
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Incomes',
                    'data' => $months_incomes,
                    'backgroundColor' => 'rgba(234, 179, 8, 0.1)',
                    'borderColor' => '#eab308',
                    'hoverBackgroundColor' => 'rgba(234, 179, 8, 0.2)',
                ],
                [
                    'label' => 'Expenses',  
                    'data' => $months_expenses,
                    'backgroundColor' => 'rgba(255, 0, 0, 0.1)',
                    'borderColor' => '#f43f5e',
                    'hoverBackgroundColor' => 'rgba(255, 0, 0, 0.2)',
                ],
                [
                    'label' => 'Balance',
                    'data' => $months_balance,
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'hoverBackgroundColor' => 'rgba(139, 92, 246, 0.3)',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {

        $years = Transaction::query()->selectRaw('YEAR(date) as year')
                                        ->where(['user_id' => auth()->id()])
                                        ->groupBy('year')->get();

        $years_list = [];

        for ($i = 0; $i < count($years); $i++) {
            $years_list[$years[$i]->year] = $years[$i]->year;
        }

        return $years_list;
    }
}
