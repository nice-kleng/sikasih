<?php

namespace App\Filament\Widgets;

use App\Models\SkriningRisiko;
use Filament\Widgets\ChartWidget;

class RisikoKehamilanChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Kategori Risiko Kehamilan';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $krr = SkriningRisiko::where('kategori_risiko', 'KRR')->count();
        $krt = SkriningRisiko::where('kategori_risiko', 'KRT')->count();
        $krst = SkriningRisiko::where('kategori_risiko', 'KRST')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => [$krr, $krt, $krst],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.8)',  // green for KRR
                        'rgba(251, 191, 36, 0.8)',  // yellow for KRT
                        'rgba(239, 68, 68, 0.8)',   // red for KRST
                    ],
                    'borderColor' => [
                        'rgba(34, 197, 94, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(239, 68, 68, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => [
                'Risiko Rendah (KRR)',
                'Risiko Tinggi (KRT)',
                'Risiko Sangat Tinggi (KRST)',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
