<?php

namespace App\Filament\Widgets;

use App\Models\Puskesmas;
use App\Models\IbuHamil;
use App\Models\PemeriksaanAnc;
use App\Models\SkriningRisiko;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Puskesmas
        $totalPuskesmas = Puskesmas::count();
        $puskesmasAktif = Puskesmas::where('status', 'aktif')->count();

        // Total Ibu Hamil
        $totalIbuHamil = IbuHamil::count();
        $ibuHamilAktif = IbuHamil::where('status_kehamilan', 'hamil')->count();
        $ibuHamilBulanIni = IbuHamil::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total Pemeriksaan ANC
        $totalPemeriksaan = PemeriksaanAnc::count();
        $pemeriksaanBulanIni = PemeriksaanAnc::whereMonth('tanggal_pemeriksaan', now()->month)
            ->whereYear('tanggal_pemeriksaan', now()->year)
            ->count();
        $pemeriksaanBulanLalu = PemeriksaanAnc::whereMonth('tanggal_pemeriksaan', now()->subMonth()->month)
            ->whereYear('tanggal_pemeriksaan', now()->subMonth()->year)
            ->count();

        // Calculate percentage change
        $pemeriksaanChange = 0;
        if ($pemeriksaanBulanLalu > 0) {
            $pemeriksaanChange = (($pemeriksaanBulanIni - $pemeriksaanBulanLalu) / $pemeriksaanBulanLalu) * 100;
        }

        // Skrining Risiko
        $skriningRisikoTinggi = SkriningRisiko::where('kategori_risiko', 'KRT')->count();
        $skriningRisikoSangatTinggi = SkriningRisiko::where('kategori_risiko', 'KRST')->count();
        $totalRisikoTinggi = $skriningRisikoTinggi + $skriningRisikoSangatTinggi;

        // Ibu Hamil per Trimester
        $trimester1 = IbuHamil::where('status_kehamilan', 'hamil')
            ->whereBetween('usia_kehamilan_minggu', [0, 13])
            ->count();
        $trimester2 = IbuHamil::where('status_kehamilan', 'hamil')
            ->whereBetween('usia_kehamilan_minggu', [14, 27])
            ->count();
        $trimester3 = IbuHamil::where('status_kehamilan', 'hamil')
            ->whereBetween('usia_kehamilan_minggu', [28, 42])
            ->count();

        return [
            Stat::make('Total Puskesmas', $totalPuskesmas)
                ->description("{$puskesmasAktif} Puskesmas Aktif")
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success')
                ->chart([7, 8, 9, 10, 11, 12, $totalPuskesmas]),

            Stat::make('Ibu Hamil Aktif', $ibuHamilAktif)
                ->description("{$ibuHamilBulanIni} pendaftaran bulan ini")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([50, 60, 70, 80, 90, 100, $ibuHamilAktif]),

            Stat::make('Pemeriksaan ANC Bulan Ini', $pemeriksaanBulanIni)
                ->description(
                    $pemeriksaanChange >= 0
                        ? number_format(abs($pemeriksaanChange), 1) . '% meningkat'
                        : number_format(abs($pemeriksaanChange), 1) . '% menurun'
                )
                ->descriptionIcon($pemeriksaanChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($pemeriksaanChange >= 0 ? 'success' : 'danger')
                ->chart([$pemeriksaanBulanLalu, $pemeriksaanBulanIni]),

            Stat::make('Kehamilan Risiko Tinggi', $totalRisikoTinggi)
                ->description("{$skriningRisikoSangatTinggi} Risiko Sangat Tinggi (KRST)")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([5, 10, 15, 20, 25, $totalRisikoTinggi]),

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
