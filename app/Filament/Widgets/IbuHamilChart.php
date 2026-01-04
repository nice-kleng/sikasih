<?php

namespace App\Filament\Widgets;

use App\Models\IbuHamil;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IbuHamilChart extends ChartWidget
{
    protected static ?string $heading = 'Pendaftaran Ibu Hamil (6 Bulan Terakhir)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::model(IbuHamil::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Ibu Hamil Terdaftar',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(255, 107, 157, 0.2)',
                    'borderColor' => 'rgba(255, 107, 157, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => date('M Y', strtotime($value->date))),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
