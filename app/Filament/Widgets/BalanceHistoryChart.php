<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use DateTime;
use IntlDateFormatter;

class BalanceHistoryChart extends ChartWidget
{
    protected static ?string $heading = 'Balance History';

    public ?string $filter = '2023';

    public function getDescription(): ?string
    {
        return 'The balance of every month throughout the selected year.';
    }

    protected function getData(): array
    {   
        $activeFilter = $this->filter;

        $incomes = Transaction::query()->selectRaw('MONTH(date) as month, SUM(value) as value')
                                        ->whereYear('date', $this->filter)
                                        ->where(['user_id' => auth()->id(), 'type' => 'income'])
                                        ->groupBy('month')->get();

        $expenses = Transaction::query()->selectRaw('MONTH(date) as month, SUM(value) as value')
                                        ->whereYear('date', $this->filter)
                                        ->where(['user_id' => auth()->id(), 'type' => 'expense'])
                                        ->groupBy('month')->get();
        $months = [];
        $balances = [];
        
        for ($i = 0; $i < count($incomes); $i++) {
            $balance = $incomes[$i]['value'] - $expenses[$i]['value'];
            array_push($balances, $balance);

            //Convertendo o inteiro para o nome do mês correspondente e adicionando ao array months
            $month = DateTime::createFromFormat('!m', $incomes[$i]->month);
            $formatter = new IntlDateFormatter('en-US', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMM');
            array_push($months, $formatter->format($month));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Balance',
                    'data' => $balances,
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

        // dd($years_list);
        return $years_list;
    }
}
