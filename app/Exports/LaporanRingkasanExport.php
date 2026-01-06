<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanRingkasanExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, ShouldAutoSize
{
    protected $statistik;
    protected $puskesmas;
    protected $periode;

    public function __construct($statistik, $puskesmas, $periode)
    {
        $this->statistik = $statistik;
        $this->puskesmas = $puskesmas;
        $this->periode = $periode;
    }

    public function collection()
    {
        $data = collect([
            // Header Puskesmas
            ['LAPORAN RINGKASAN PUSKESMAS', '', '', ''],
            [$this->puskesmas->nama_puskesmas ?? 'N/A', '', '', ''],
            ['Periode: ' . (is_string($this->periode['dari']) ? $this->periode['dari'] : $this->periode['dari']->format('d/m/Y')) . ' - ' . (is_string($this->periode['sampai']) ? $this->periode['sampai'] : $this->periode['sampai']->format('d/m/Y')), '', '', ''],
            ['', '', '', ''],

            // Data Utama
            ['RINGKASAN DATA', '', '', ''],
            ['Indikator', 'Jumlah', 'Target', 'Capaian (%)'],
            ['Total Ibu Hamil Aktif', $this->statistik['total_ibu_hamil'] ?? 0, '-', '-'],
            ['Total Pemeriksaan ANC', $this->statistik['total_pemeriksaan'] ?? 0, '-', '-'],
            ['Cakupan K1', $this->statistik['cakupan_k1'] ?? 0, $this->statistik['total_ibu_hamil'] ?? 0, $this->statistik['persentase_k1'] ?? 0],
            ['Cakupan K4', $this->statistik['cakupan_k4'] ?? 0, $this->statistik['total_ibu_hamil'] ?? 0, $this->statistik['persentase_k4'] ?? 0],
            ['Total Tenaga Kesehatan', $this->statistik['total_tenaga'] ?? 0, '-', '-'],
            ['', '', '', ''],

            // Skrining Risiko
            ['SKRINING RISIKO KEHAMILAN', '', '', ''],
            ['Kategori Risiko', 'Jumlah', 'Persentase', ''],
            ['Risiko Rendah (KRR)', $this->statistik['skrining_krr'] ?? 0, $this->getPercentage('skrining_krr') . '%', ''],
            ['Risiko Tinggi (KRT)', $this->statistik['skrining_krt'] ?? 0, $this->getPercentage('skrining_krt') . '%', ''],
            ['Risiko Sangat Tinggi (KRST)', $this->statistik['skrining_krst'] ?? 0, $this->getPercentage('skrining_krst') . '%', ''],
            ['Total Skrining', $this->getTotalSkrining(), '100%', ''],
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styles
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EC4899'],
            ],
        ]);

        $sheet->getStyle('A2:D2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Section headers
        $sheet->getStyle('A5:D5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FBCFE8'],
            ],
        ]);

        $sheet->getStyle('A13:D13')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FBCFE8'],
            ],
        ]);

        // Table headers
        $sheet->getStyle('A6:D6')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F3F4F6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('A14:D14')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F3F4F6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Data borders
        $sheet->getStyle('A7:D11')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('A15:D18')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Merge cells for header
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');

        return [];
    }

    public function title(): string
    {
        return 'Laporan Ringkasan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 15,
            'C' => 15,
            'D' => 15,
        ];
    }

    private function getTotalSkrining()
    {
        return ($this->statistik['skrining_krr'] ?? 0) +
            ($this->statistik['skrining_krt'] ?? 0) +
            ($this->statistik['skrining_krst'] ?? 0);
    }

    private function getPercentage($key)
    {
        $total = $this->getTotalSkrining();
        if ($total == 0) return 0;

        return round((($this->statistik[$key] ?? 0) / $total) * 100, 2);
    }
}
