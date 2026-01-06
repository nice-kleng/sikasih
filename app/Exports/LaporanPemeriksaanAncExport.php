<?php

namespace App\Exports;

use App\Models\PemeriksaanAnc;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanPemeriksaanAncExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
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
        return PemeriksaanAnc::query()
            ->where('puskesmas_id', $this->puskesmasId)
            ->whereBetween('tanggal_pemeriksaan', [$this->periode['dari'], $this->periode['sampai']])
            ->with(['ibuHamil', 'tenagaKesehatan.user'])
            ->orderBy('tanggal_pemeriksaan', 'desc');
    }

    public function headings(): array
    {
        return [
            'No. Pemeriksaan',
            'Tanggal',
            'Ibu Hamil',
            'No. RM',
            'Kunjungan Ke',
            'UK (minggu)',
            'BB (kg)',
            'TD (mmHg)',
            'DJJ (bpm)',
            'TFU (cm)',
            'Letak Janin',
            'HB (g/dL)',
            'Diagnosis',
            'Status',
            'Pemeriksa',
        ];
    }

    public function map($pemeriksaan): array
    {
        return [
            $pemeriksaan->no_pemeriksaan,
            $pemeriksaan->tanggal_pemeriksaan->format('d/m/Y'),
            $pemeriksaan->ibuHamil->nama_lengkap ?? '-',
            $pemeriksaan->ibuHamil->no_rm ?? '-',
            $pemeriksaan->kunjungan_ke,
            $pemeriksaan->usia_kehamilan_minggu,
            $pemeriksaan->berat_badan,
            $pemeriksaan->tekanan_darah,
            $pemeriksaan->djj ?? '-',
            $pemeriksaan->tinggi_fundus ?? '-',
            $pemeriksaan->letak_janin ?? '-',
            $pemeriksaan->hb ?? '-',
            $pemeriksaan->diagnosis ?? '-',
            ucfirst($pemeriksaan->status_pemeriksaan),
            $pemeriksaan->tenagaKesehatan->user->nama ?? '-',
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
        return 'Pemeriksaan ANC';
    }
}
