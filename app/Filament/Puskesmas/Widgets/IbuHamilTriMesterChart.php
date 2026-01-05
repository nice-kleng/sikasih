<?php

namespace App\Filament\Puskesmas\Widgets;

use App\Models\IbuHamil;
use Filament\Widgets\ChartWidget;

class IbuHamilTrimesterChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Ibu Hamil per Trimester';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $user = auth()->user();
        $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;

        $trimester1 = IbuHamil::where('puskesmas_id', $puskesmasId)
            ->where('status_kehamilan', 'hamil')
            ->whereBetween('usia_kehamilan_minggu', [0, 13])
            ->count();

        $trimester2 = IbuHamil::where('puskesmas_id', $puskesmasId)
            ->where('status_kehamilan', 'hamil')
            ->whereBetween('usia_kehamilan_minggu', [14, 27])
            ->count();

        $trimester3 = IbuHamil::where('puskesmas_id', $puskesmasId)
            ->where('status_kehamilan', 'hamil')
            ->whereBetween('usia_kehamilan_minggu', [28, 42])
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Ibu Hamil',
                    'data' => [$trimester1, $trimester2, $trimester3],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',   // blue for T1
                        'rgba(251, 191, 36, 0.8)',   // yellow for T2
                        'rgba(34, 197, 94, 0.8)',    // green for T3
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(34, 197, 94, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => [
                'Trimester 1 (0-13 minggu)',
                'Trimester 2 (14-27 minggu)',
                'Trimester 3 (28-42 minggu)',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                    'display' => false,
                ],
            ],
        ];
    }
}
