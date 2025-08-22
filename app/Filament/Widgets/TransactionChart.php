<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;

class TransactionChart extends ChartWidget
{
    protected static ?string $heading = 'Transactions (30 Days)';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Trend::query(Transaction::where('status', 'success'))
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth()
            )
            ->perDay()
            ->sum('total');
            
        return [
            'datasets' => [
                [
                    'label' => 'Total Transactions',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
