<?php

namespace App\Http\Controllers;

use App\Models\IbuHamil;
use App\Models\PemeriksaanAnc;
use App\Models\SkriningRisiko;
use App\Models\Artikel;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ibuHamil = $user->ibuHamil;

        if (!$ibuHamil) {
            return redirect()->route('app.profil')->with('warning', 'Silakan lengkapi data kehamilan Anda.');
        }

        // Get pregnancy info
        $usiaKehamilan = $ibuHamil->usia_kehamilan_minggu ?? 0;
        $trimester = $ibuHamil->trimester ?? 1;
        $hpl = $ibuHamil->hpl;
        $hariLagi = $hpl ? now()->diffInDays($hpl, false) : null;

        // Get latest pemeriksaan
        $latestPemeriksaan = PemeriksaanAnc::where('ibu_hamil_id', $ibuHamil->id)
            ->latest('tanggal_pemeriksaan')
            ->first();

        // Get latest skrining
        $latestSkrining = SkriningRisiko::where('ibu_hamil_id', $ibuHamil->id)
            ->latest('tanggal_skrining')
            ->first();

        // Count pemeriksaan
        $jumlahPemeriksaan = PemeriksaanAnc::where('ibu_hamil_id', $ibuHamil->id)->count();

        // Get recommended articles based on trimester
        $artikelRekomendasi = Artikel::where('status', 'published')
            ->where('kategori', 'like', '%trimester ' . $trimester . '%')
            ->orWhere('kategori', 'like', '%umum%')
            ->latest('published_at')
            ->take(3)
            ->get();

        // Daily tips based on pregnancy week
        $tips = $this->getDailyTips($usiaKehamilan, $trimester);

        // Check if account is pending
        $isPending = $user->status === 'pending';

        return view('app.beranda', compact(
            'ibuHamil',
            'usiaKehamilan',
            'trimester',
            'hpl',
            'hariLagi',
            'latestPemeriksaan',
            'latestSkrining',
            'jumlahPemeriksaan',
            'artikelRekomendasi',
            'tips',
            'isPending'
        ));
    }

    private function getDailyTips($usiaKehamilan, $trimester)
    {
        $tips = [
            1 => [
                'Minum air putih minimal 8 gelas per hari',
                'Konsumsi makanan bergizi seimbang',
                'Istirahat yang cukup 7-8 jam per hari',
                'Hindari stress berlebihan',
                'Jangan lupa minum vitamin kehamilan',
            ],
            2 => [
                'Lakukan olahraga ringan seperti jalan kaki',
                'Perbanyak konsumsi buah dan sayur',
                'Jaga kebersihan diri',
                'Rutin kontrol ke puskesmas',
                'Hindari makanan mentah',
            ],
            3 => [
                'Siapkan perlengkapan persalinan',
                'Pelajari teknik pernapasan untuk persalinan',
                'Jaga pola makan yang sehat',
                'Hindari aktivitas berat',
                'Perhatikan gerakan janin',
            ],
        ];

        $trimesterTips = $tips[$trimester] ?? $tips[1];
        $dayIndex = date('w'); // 0-6

        return $trimesterTips[$dayIndex % count($trimesterTips)];
    }
}
