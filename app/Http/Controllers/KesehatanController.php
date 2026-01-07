<?php

namespace App\Http\Controllers;

use App\Models\IbuHamil;
use App\Models\PemeriksaanAnc;
use App\Models\SkriningRisiko;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KesehatanController extends Controller
{
    /**
     * Show kesehatan page (pemeriksaan + skrining)
     */
    public function index()
    {
        $user = auth()->user();
        $ibuHamil = $user->ibuHamil;

        if (!$ibuHamil) {
            return redirect()->route('app.profil')->with('warning', 'Silakan lengkapi data kehamilan Anda.');
        }

        // Get all pemeriksaan
        $pemeriksaan = PemeriksaanAnc::where('ibu_hamil_id', $ibuHamil->id)
            ->with('tenagaKesehatan.user')
            ->latest('tanggal_pemeriksaan')
            ->get();

        // Get all skrining
        $skrining = SkriningRisiko::where('ibu_hamil_id', $ibuHamil->id)
            ->with('tenagaKesehatan.user')
            ->latest('tanggal_skrining')
            ->get();

        // Check if pending
        $isPending = $user->status === 'pending';

        return view('app.kesehatan', compact('ibuHamil', 'pemeriksaan', 'skrining', 'isPending'));
    }

    /**
     * Show skrining form
     */
    public function createSkrining()
    {
        $user = auth()->user();
        $ibuHamil = $user->ibuHamil;

        if (!$ibuHamil) {
            return redirect()->route('app.profil')->with('warning', 'Silakan lengkapi data kehamilan Anda.');
        }

        // Check if pending - allow skrining for pending users
        $isPending = $user->status === 'pending';

        // Get faktor risiko list
        $faktorRisiko = $this->getFaktorRisikoList();

        return view('app.skrining-create', compact('ibuHamil', 'faktorRisiko', 'isPending'));
    }

    /**
     * Store skrining mandiri
     */
    public function storeSkrining(Request $request)
    {
        $user = auth()->user();
        $ibuHamil = $user->ibuHamil;

        if (!$ibuHamil) {
            return redirect()->route('app.profil')->with('error', 'Data kehamilan tidak ditemukan.');
        }

        $validated = $request->validate([
            'faktor_risiko' => 'required|array',
            'faktor_risiko.*' => 'string',
            'catatan' => 'nullable|string',
        ]);

        // Calculate score
        $totalSkor = 0;
        $faktorRisikoTerpilih = [];
        $faktorRisikoList = $this->getFaktorRisikoList();

        foreach ($validated['faktor_risiko'] as $key) {
            if (isset($faktorRisikoList[$key])) {
                $totalSkor += $faktorRisikoList[$key]['skor'];
                $faktorRisikoTerpilih[] = $faktorRisikoList[$key]['nama'];
            }
        }

        // Determine kategori
        if ($totalSkor <= 2) {
            $kategori = 'KRR';
            $rekomendasi = 'Bersalin di Puskesmas/Bidan';
        } elseif ($totalSkor <= 6) {
            $kategori = 'KRT';
            $rekomendasi = 'Bersalin di Puskesmas PONED atau Rumah Sakit';
        } else {
            $kategori = 'KRST';
            $rekomendasi = 'Bersalin di Rumah Sakit';
        }

        // Generate no_skrining
        $lastSkrining = SkriningRisiko::where('puskesmas_id', $ibuHamil->puskesmas_id)
            ->latest('id')
            ->first();
        $lastNumber = $lastSkrining ? (int) substr($lastSkrining->no_skrining, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $noSkrining = 'SKR-' . date('Y') . '-' . $newNumber;

        // Create skrining
        SkriningRisiko::create([
            'ibu_hamil_id' => $ibuHamil->id,
            'puskesmas_id' => $ibuHamil->puskesmas_id,
            'tenaga_kesehatan_id' => null, // Mandiri
            'no_skrining' => $noSkrining,
            'tanggal_skrining' => now(),
            'usia_kehamilan_minggu' => $ibuHamil->usia_kehamilan_minggu,
            'jenis_skrining' => 'mandiri',
            'faktor_risiko' => json_encode($faktorRisikoTerpilih),
            'total_skor' => $totalSkor,
            'kategori_risiko' => $kategori,
            'rekomendasi_tempat_bersalin' => $rekomendasi,
            'catatan' => $validated['catatan'],
            'status' => 'selesai',
        ]);

        return redirect()->route('app.kesehatan')
            ->with('success', 'Skrining mandiri berhasil disimpan! Kategori risiko Anda: ' . $kategori);
    }

    /**
     * Get faktor risiko list with scores
     */
    private function getFaktorRisikoList()
    {
        return [
            'terlalu_muda' => [
                'nama' => 'Terlalu muda (< 16 tahun)',
                'skor' => 4,
                'kategori' => 'Umur & Paritas'
            ],
            'terlalu_tua' => [
                'nama' => 'Terlalu tua (> 35 tahun)',
                'skor' => 4,
                'kategori' => 'Umur & Paritas'
            ],
            'anak_lebih_4' => [
                'nama' => 'Anak lebih dari 4',
                'skor' => 4,
                'kategori' => 'Umur & Paritas'
            ],
            'jarak_kehamilan' => [
                'nama' => 'Jarak kehamilan terakhir < 2 tahun',
                'skor' => 4,
                'kategori' => 'Umur & Paritas'
            ],
            'kurang_gizi' => [
                'nama' => 'Kurang gizi (KEK)',
                'skor' => 4,
                'kategori' => 'Status Gizi'
            ],
            'pendek' => [
                'nama' => 'Tinggi badan < 145 cm',
                'skor' => 4,
                'kategori' => 'Riwayat Kesehatan'
            ],
            'riwayat_sc' => [
                'nama' => 'Riwayat operasi caesar',
                'skor' => 8,
                'kategori' => 'Riwayat Obstetri'
            ],
            'riwayat_perdarahan' => [
                'nama' => 'Riwayat perdarahan',
                'skor' => 8,
                'kategori' => 'Riwayat Obstetri'
            ],
            'riwayat_preeklamsia' => [
                'nama' => 'Riwayat preeklamsia/eklamsia',
                'skor' => 8,
                'kategori' => 'Riwayat Obstetri'
            ],
            'riwayat_bb_rendah' => [
                'nama' => 'Bayi terakhir BB < 2500 gram',
                'skor' => 4,
                'kategori' => 'Riwayat Obstetri'
            ],
            'riwayat_bb_tinggi' => [
                'nama' => 'Bayi terakhir BB > 4000 gram',
                'skor' => 4,
                'kategori' => 'Riwayat Obstetri'
            ],
            'riwayat_kematian' => [
                'nama' => 'Riwayat kematian janin/neonatal',
                'skor' => 4,
                'kategori' => 'Riwayat Obstetri'
            ],
            'riwayat_cacat' => [
                'nama' => 'Riwayat bayi cacat bawaan',
                'skor' => 4,
                'kategori' => 'Riwayat Obstetri'
            ],
            'hamil_kembar' => [
                'nama' => 'Hamil kembar',
                'skor' => 4,
                'kategori' => 'Kondisi Kehamilan'
            ],
            'hidramnion' => [
                'nama' => 'Hidramnion/oligohidramnion',
                'skor' => 4,
                'kategori' => 'Kondisi Kehamilan'
            ],
            'kelainan_letak' => [
                'nama' => 'Kelainan letak janin',
                'skor' => 8,
                'kategori' => 'Kondisi Kehamilan'
            ],
            'perdarahan_hamil' => [
                'nama' => 'Perdarahan pada kehamilan ini',
                'skor' => 8,
                'kategori' => 'Kondisi Kehamilan'
            ],
            'preeklamsia' => [
                'nama' => 'Preeklamsia/hipertensi',
                'skor' => 8,
                'kategori' => 'Penyakit Penyerta'
            ],
            'penyakit_kronis' => [
                'nama' => 'Penyakit kronis (jantung, DM, ginjal, dll)',
                'skor' => 4,
                'kategori' => 'Penyakit Penyerta'
            ],
            'anemia' => [
                'nama' => 'Anemia (HB < 11 g/dL)',
                'skor' => 4,
                'kategori' => 'Penyakit Penyerta'
            ],
        ];
    }
}
