<?php

namespace App\Exports;

use App\Models\SkriningRisiko;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanSkriningRisikoExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $puskesmasId;
    protected $periode;

    public function __construct($puskesmasId, $periode)
    {
        $this->puskesmasId = $puskesmasId;
        $this->periode = $periode;
    }

    public function query()
    {
        return SkriningRisiko::query()
            ->where('puskesmas_id', $this->puskesmasId)
            ->whereBetween('tanggal_skrining', [$this->periode['dari'], $this->periode['sampai']])
            ->with(['ibuHamil', 'tenagaKesehatan.user'])
            ->orderBy('tanggal_skrining', 'desc');
    }

    public function headings(): array
    {
        return [
            'No. Skrining',
            'Tanggal',
            'Ibu Hamil',
            'No. RM',
            'UK (minggu)',
            'Total Skor',
            'Kategori Risiko',
            'Rekomendasi Tempat',
            'Jenis Skrining',
            'Status',
            'Pemeriksa',
        ];
    }

    public function map($skrining): array
    {
        return [
            $skrining->no_skrining,
            $skrining->tanggal_skrining->format('d/m/Y'),
            $skrining->ibuHamil->nama_lengkap ?? '-',
            $skrining->ibuHamil->no_rm ?? '-',
            $skrining->usia_kehamilan_minggu ?? '-',
            $skrining->total_skor,
            $skrining->kategori_risiko,
            $skrining->rekomendasi_tempat_bersalin,
            ucfirst(str_replace('_', ' ', $skrining->jenis_skrining)),
            ucfirst($skrining->status),
            $skrining->tenagaKesehatan->user->nama ?? 'Mandiri',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EC4899'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Skrining Risiko';
    }
}
