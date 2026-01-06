<?php

namespace App\Exports;

use App\Models\IbuHamil;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanIbuHamilExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
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
        return IbuHamil::query()
            ->where('puskesmas_id', $this->puskesmasId)
            ->where('status_kehamilan', 'hamil')
            ->whereBetween('created_at', [$this->periode['dari'], $this->periode['sampai']])
            ->with(['puskesmas'])
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No. RM',
            'NIK',
            'Nama Lengkap',
            'Umur',
            'Alamat',
            'No. Telepon',
            'HPHT',
            'HPL',
            'Usia Kehamilan',
            'Trimester',
            'Status Obstetri',
            'Golongan Darah',
            'BPJS',
            'Tanggal Daftar',
        ];
    }

    public function map($ibuHamil): array
    {
        return [
            $ibuHamil->no_rm,
            $ibuHamil->nik,
            $ibuHamil->nama_lengkap,
            $ibuHamil->umur . ' tahun',
            $ibuHamil->alamat_lengkap,
            $ibuHamil->user->no_telepon ?? '-',
            $ibuHamil->hpht?->format('d/m/Y') ?? '-',
            $ibuHamil->hpl?->format('d/m/Y') ?? '-',
            $ibuHamil->usia_kehamilan_minggu . ' minggu',
            'Trimester ' . $ibuHamil->trimester,
            $ibuHamil->statusObstetri,
            $ibuHamil->golongan_darah ?? '-',
            $ibuHamil->memiliki_bpjs == 'Ya' ? $ibuHamil->no_bpjs : 'Tidak',
            $ibuHamil->created_at->format('d/m/Y'),
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
        return 'Data Ibu Hamil';
    }
}
