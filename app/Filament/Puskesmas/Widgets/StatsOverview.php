<?php

namespace App\Filament\Puskesmas\Widgets;

use App\Models\IbuHamil;
use App\Models\PemeriksaanAnc;
use App\Models\SkriningRisiko;
use App\Models\TenagaKesehatan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Get puskesmas_id dari user yang login
        $user = auth()->user();
        $puskesmasId = $user->puskesmas?->id ?? $user->tenagaKesehatan?->puskesmas_id;

        // Total Tenaga Kesehatan
        $totalTenaga = TenagaKesehatan::where('puskesmas_id', $puskesmasId)
            ->where('status', 'aktif')
            ->count();

        // Total Ibu Hamil Aktif
        $ibuHamilAktif = IbuHamil::where('puskesmas_id', $puskesmasId)
            ->where('status_kehamilan', 'hamil')
            ->count();

        $ibuHamilBulanIni = IbuHamil::where('puskesmas_id', $puskesmasId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Pemeriksaan ANC Bulan Ini
        $pemeriksaanBulanIni = PemeriksaanAnc::where('puskesmas_id', $puskesmasId)
            ->whereMonth('tanggal_pemeriksaan', now()->month)
            ->whereYear('tanggal_pemeriksaan', now()->year)
            ->count();

        $pemeriksaanBulanLalu = PemeriksaanAnc::where('puskesmas_id', $puskesmasId)
            ->whereMonth('tanggal_pemeriksaan', now()->subMonth()->month)
            ->whereYear('tanggal_pemeriksaan', now()->subMonth()->year)
            ->count();

        $pemeriksaanChange = 0;
        if ($pemeriksaanBulanLalu > 0) {
            $pemeriksaanChange = (($pemeriksaanBulanIni - $pemeriksaanBulanLalu) / $pemeriksaanBulanLalu) * 100;
        }

        // Kehamilan Risiko Tinggi
        $risikoTinggi = SkriningRisiko::where('puskesmas_id', $puskesmasId)
            ->whereIn('kategori_risiko', ['KRT', 'KRST'])
            ->count();

        $risikoSangatTinggi = SkriningRisiko::where('puskesmas_id', $puskesmasId)
            ->where('kategori_risiko', 'KRST')
            ->count();

        // Per Trimester
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
            Stat::make('Tenaga Kesehatan', $totalTenaga)
                ->description('Bidan & Dokter Aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Ibu Hamil Aktif', $ibuHamilAktif)
                ->description("{$ibuHamilBulanIni} pendaftaran bulan ini")
                ->descriptionIcon('heroicon-m-heart')
                ->color('success')
                ->chart([40, 50, 60, 70, 80, $ibuHamilAktif]),

            Stat::make('ANC Bulan Ini', $pemeriksaanBulanIni)
                ->description(
                    $pemeriksaanChange >= 0
                        ? number_format(abs($pemeriksaanChange), 1) . '% meningkat'
                        : number_format(abs($pemeriksaanChange), 1) . '% menurun'
                )
                ->descriptionIcon($pemeriksaanChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($pemeriksaanChange >= 0 ? 'success' : 'danger')
                ->chart([$pemeriksaanBulanLalu, $pemeriksaanBulanIni]),

            Stat::make('Kehamilan Risiko Tinggi', $risikoTinggi)
                ->description("{$risikoSangatTinggi} KRST memerlukan perhatian khusus")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Trimester 1', $trimester1)
                ->description('0-13 minggu')
                ->descriptionIcon('heroicon-m-heart')
                ->color('info'),

            Stat::make('Trimester 2', $trimester2)
                ->description('14-27 minggu')
                ->descriptionIcon('heroicon-m-heart')
                ->color('warning'),

            Stat::make('Trimester 3', $trimester3)
                ->description('28-42 minggu')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
