<?php

namespace App\Filament\Puskesmas\Pages;

use App\Models\IbuHamil;
use App\Models\PemeriksaanAnc;
use App\Models\SkriningRisiko;
use App\Models\TenagaKesehatan;
use App\Exports\LaporanRingkasanExport;
use App\Exports\LaporanIbuHamilExport;
use App\Exports\LaporanPemeriksaanAncExport;
use App\Exports\LaporanSkriningRisikoExport;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class Laporan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.puskesmas.pages.laporan';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $title = 'Laporan Puskesmas';

    public ?array $data = [];

    public $statistik = [];

    public function mount(): void
    {
        $this->form->fill([
            'periode_dari' => now()->startOfMonth(),
            'periode_sampai' => now(),
            'jenis_laporan' => 'ringkasan',
            'format_export' => 'excel',
        ]);

        $this->loadStatistik();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('periode_dari')
                    ->label('Periode Dari')
                    ->required()
                    ->native(false)
                    ->default(now()->startOfMonth()),
                DatePicker::make('periode_sampai')
                    ->label('Periode Sampai')
                    ->required()
                    ->native(false)
                    ->default(now()),
                Select::make('jenis_laporan')
                    ->label('Jenis Laporan')
                    ->options([
                        'ringkasan' => 'Laporan Ringkasan',
                        'ibu_hamil' => 'Laporan Ibu Hamil',
                        'pemeriksaan_anc' => 'Laporan Pemeriksaan ANC',
                        'skrining_risiko' => 'Laporan Skrining Risiko',
                    ])
                    ->default('ringkasan')
                    ->required(),
                Radio::make('format_export')
                    ->label('Format Export')
                    ->options([
                        'excel' => 'Excel (.xlsx)',
                        'pdf' => 'PDF (.pdf)',
                    ])
                    ->default('excel')
                    ->inline()
                    ->required(),
            ])
            ->statePath('data')
            ->columns(4);
    }

    public function loadStatistik(): void
    {
        $user = auth()->user();
        $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;

        $periodeAwal = $this->data['periode_dari'] ?? now()->startOfMonth();
        $periodeAkhir = $this->data['periode_sampai'] ?? now();

        // Total Ibu Hamil
        $this->statistik['total_ibu_hamil'] = IbuHamil::where('puskesmas_id', $puskesmasId)
            ->where('status_kehamilan', 'hamil')
            ->count();

        // Total Pemeriksaan
        $this->statistik['total_pemeriksaan'] = PemeriksaanAnc::where('puskesmas_id', $puskesmasId)
            ->whereBetween('tanggal_pemeriksaan', [$periodeAwal, $periodeAkhir])
            ->count();

        // Skrining Risiko
        $this->statistik['skrining_krr'] = SkriningRisiko::where('puskesmas_id', $puskesmasId)
            ->where('kategori_risiko', 'KRR')
            ->whereBetween('tanggal_skrining', [$periodeAwal, $periodeAkhir])
            ->count();

        $this->statistik['skrining_krt'] = SkriningRisiko::where('puskesmas_id', $puskesmasId)
            ->where('kategori_risiko', 'KRT')
            ->whereBetween('tanggal_skrining', [$periodeAwal, $periodeAkhir])
            ->count();

        $this->statistik['skrining_krst'] = SkriningRisiko::where('puskesmas_id', $puskesmasId)
            ->where('kategori_risiko', 'KRST')
            ->whereBetween('tanggal_skrining', [$periodeAwal, $periodeAkhir])
            ->count();

        // Tenaga Kesehatan
        $this->statistik['total_tenaga'] = TenagaKesehatan::where('puskesmas_id', $puskesmasId)
            ->where('status', 'aktif')
            ->count();

        // Cakupan K1 & K4
        $ibuHamilIds = IbuHamil::where('puskesmas_id', $puskesmasId)
            ->where('status_kehamilan', 'hamil')
            ->pluck('id');

        $this->statistik['cakupan_k1'] = PemeriksaanAnc::whereIn('ibu_hamil_id', $ibuHamilIds)
            ->where('kunjungan_ke', 1)
            ->whereBetween('tanggal_pemeriksaan', [$periodeAwal, $periodeAkhir])
            ->distinct('ibu_hamil_id')
            ->count();

        $this->statistik['cakupan_k4'] = PemeriksaanAnc::whereIn('ibu_hamil_id', $ibuHamilIds)
            ->where('kunjungan_ke', '>=', 4)
            ->whereBetween('tanggal_pemeriksaan', [$periodeAwal, $periodeAkhir])
            ->distinct('ibu_hamil_id')
            ->count();

        // Persentase
        $totalIbuHamil = $this->statistik['total_ibu_hamil'] ?: 1;
        $this->statistik['persentase_k1'] = round(($this->statistik['cakupan_k1'] / $totalIbuHamil) * 100, 2);
        $this->statistik['persentase_k4'] = round(($this->statistik['cakupan_k4'] / $totalIbuHamil) * 100, 2);
    }

    public function generate(): void
    {
        $this->loadStatistik();

        Notification::make()
            ->title('Berhasil!')
            ->success()
            ->body('Laporan berhasil di-generate!')
            ->send();
    }

    public function export()
    {
        $jenis = $this->data['jenis_laporan'] ?? 'ringkasan';
        $format = $this->data['format_export'] ?? 'excel';

        $user = auth()->user();
        $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;
        $puskesmas = \App\Models\Puskesmas::find($puskesmasId);

        $periode = [
            'dari' => $this->data['periode_dari'] ?? now()->startOfMonth(),
            'sampai' => $this->data['periode_sampai'] ?? now(),
        ];

        $filename = 'laporan-' . $jenis . '-' . now()->format('Y-m-d-His');

        try {
            if ($format === 'excel') {
                return $this->exportExcel($jenis, $puskesmasId, $puskesmas, $periode, $filename);
            } else {
                return $this->exportPdf($jenis, $puskesmasId, $puskesmas, $periode, $filename);
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Export Gagal!')
                ->danger()
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->send();
        }
    }

    protected function exportExcel($jenis, $puskesmasId, $puskesmas, $periode, $filename)
    {
        $export = match ($jenis) {
            'ringkasan' => new LaporanRingkasanExport($this->statistik, $puskesmas, $periode),
            'ibu_hamil' => new LaporanIbuHamilExport($puskesmasId, $periode),
            'pemeriksaan_anc' => new LaporanPemeriksaanAncExport($puskesmasId, $periode),
            'skrining_risiko' => new LaporanSkriningRisikoExport($puskesmasId, $periode),
            default => new LaporanRingkasanExport($this->statistik, $puskesmas, $periode),
        };

        Notification::make()
            ->title('Export Berhasil!')
            ->success()
            ->body('Laporan Excel berhasil di-download!')
            ->send();

        return Excel::download($export, $filename . '.xlsx');
    }

    protected function exportPdf($jenis, $puskesmasId, $puskesmas, $periode, $filename)
    {
        $view = match ($jenis) {
            'ringkasan' => 'pdf.laporan-ringkasan',
            'ibu_hamil' => 'pdf.laporan-ibu-hamil',
            'pemeriksaan_anc' => 'pdf.laporan-pemeriksaan-anc',
            'skrining_risiko' => 'pdf.laporan-skrining-risiko',
            default => 'pdf.laporan-ringkasan',
        };

        $data = [
            'statistik' => $this->statistik,
            'puskesmas' => $puskesmas,
            'periode' => $periode,
        ];

        // Add specific data based on report type
        if ($jenis === 'ibu_hamil') {
            $data['dataIbuHamil'] = IbuHamil::where('puskesmas_id', $puskesmasId)
                ->where('status_kehamilan', 'hamil')
                ->whereBetween('created_at', [$periode['dari'], $periode['sampai']])
                ->with(['puskesmas'])
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($jenis === 'pemeriksaan_anc') {
            $data['dataPemeriksaan'] = PemeriksaanAnc::where('puskesmas_id', $puskesmasId)
                ->whereBetween('tanggal_pemeriksaan', [$periode['dari'], $periode['sampai']])
                ->with(['ibuHamil', 'tenagaKesehatan.user'])
                ->orderBy('tanggal_pemeriksaan', 'desc')
                ->get();
        } elseif ($jenis === 'skrining_risiko') {
            $data['dataSkrining'] = SkriningRisiko::where('puskesmas_id', $puskesmasId)
                ->whereBetween('tanggal_skrining', [$periode['dari'], $periode['sampai']])
                ->with(['ibuHamil', 'tenagaKesehatan.user'])
                ->orderBy('tanggal_skrining', 'desc')
                ->get();
        }

        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        Notification::make()
            ->title('Export Berhasil!')
            ->success()
            ->body('Laporan PDF berhasil di-download!')
            ->send();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename . '.pdf');
    }
}
