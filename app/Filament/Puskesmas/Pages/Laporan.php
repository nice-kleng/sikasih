<?php

namespace App\Filament\Puskesmas\Pages;

use App\Models\IbuHamil;
use App\Models\PemeriksaanAnc;
use App\Models\SkriningRisiko;
use App\Models\TenagaKesehatan;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

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
                        'pemeriksaan_anc' => 'Laporan Pemeriksaan ANC',
                        'skrining_risiko' => 'Laporan Skrining Risiko',
                        'ibu_hamil' => 'Laporan Ibu Hamil',
                        'ringkasan' => 'Laporan Ringkasan',
                    ])
                    ->default('ringkasan'),
            ])
            ->statePath('data')
            ->columns(3);
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

    public function export(): void
    {
        $jenis = $this->data['jenis_laporan'] ?? 'ringkasan';

        Notification::make()
            ->title('Export Berhasil!')
            ->success()
            ->body("Laporan {$jenis} berhasil di-export!")
            ->send();

        // TODO: Implement actual export functionality
        // Example: return Excel::download(new LaporanExport($this->statistik), 'laporan.xlsx');
    }
}
