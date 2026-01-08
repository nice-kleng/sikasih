<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\VideoEdukasi;
use Illuminate\Http\Request;

class EdukasiController extends Controller
{
    /**
     * Show edukasi page (for authenticated users)
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'semua');
        $search = $request->get('search');
        $type = $request->get('type', 'artikel'); // artikel or video

        // Get user's trimester for recommendations
        $user = auth()->user();
        $ibuHamil = $user->ibuHamil;
        $trimester = $ibuHamil ? $ibuHamil->trimester : 1;

        if ($type === 'video') {
            // Get videos
            $query = VideoEdukasi::where('status', 'published');

            if ($kategori !== 'semua') {
                $query->where('kategori', $kategori);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            $items = $query->latest('published_at')->paginate(9);
        } else {
            // Get articles
            $query = Artikel::where('status', 'published');

            if ($kategori !== 'semua') {
                $query->where('kategori', $kategori);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('konten', 'like', "%{$search}%");
                });
            }

            $items = $query->latest('published_at')->paginate(9);
        }

        // Get categories
        $kategoris = [
            'semua' => 'Semua',
            'trimester_1' => 'Trimester 1',
            'trimester_2' => 'Trimester 2',
            'trimester_3' => 'Trimester 3',
            'nutrisi' => 'Nutrisi',
            'olahraga' => 'Olahraga',
            'persalinan' => 'Persalinan',
            'nifas' => 'Nifas',
            'umum' => 'Umum',
        ];

        return view('app.edukasi', compact('items', 'kategoris', 'kategori', 'search', 'type', 'trimester'));
    }

    /**
     * Show artikel list (PUBLIC ACCESS - no auth required)
     */
    public function artikelIndex(Request $request)
    {
        $search = $request->get('search');
        $kategori = $request->get('kategori');

        $query = Artikel::where('status', 'published')
            ->orderBy('published_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $artikels = $query->paginate(10);

        $kategoris = [
            '' => 'Semua',
            'nutrisi' => 'Nutrisi',
            'olahraga' => 'Olahraga',
            'kesehatan_mental' => 'Kesehatan Mental',
            'perkembangan_janin' => 'Perkembangan Janin',
            'persiapan_persalinan' => 'Persiapan Persalinan',
            'tips_kehamilan' => 'Tips Kehamilan',
        ];

        return view('public.artikel', compact('artikels', 'kategoris', 'search', 'kategori'));
    }

    /**
     * Show artikel detail (PUBLIC ACCESS - no auth required)
     */
    public function showArtikel($slug)
    {
        $artikel = Artikel::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $artikel->increment('views');

        // Get related articles
        $relatedArtikels = Artikel::where('status', 'published')
            ->where('kategori', $artikel->kategori)
            ->where('id', '!=', $artikel->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('app.artikel-detail', compact('artikel', 'relatedArtikels'));
    }

    /**
     * Show video list (PUBLIC ACCESS - no auth required)
     */
    public function videoIndex(Request $request)
    {
        $search = $request->get('search');
        $kategori = $request->get('kategori');

        $query = VideoEdukasi::where('status', 'published')
            ->orderBy('published_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $videos = $query->paginate(10);

        $kategoris = [
            '' => 'Semua',
            'nutrisi' => 'Nutrisi',
            'olahraga' => 'Olahraga',
            'senam_hamil' => 'Senam Hamil',
            'perkembangan_janin' => 'Perkembangan Janin',
            'persiapan_persalinan' => 'Persiapan Persalinan',
            'tips_kehamilan' => 'Tips Kehamilan',
        ];

        return view('public.video', compact('videos', 'kategoris', 'search', 'kategori'));
    }

    /**
     * Show video detail (PUBLIC ACCESS - no auth required)
     */
    public function showVideo($slug)
    {
        $video = VideoEdukasi::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $video->increment('views');

        // Get related videos
        $relatedVideos = VideoEdukasi::where('status', 'published')
            ->where('kategori', $video->kategori)
            ->where('id', '!=', $video->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('app.video-detail', compact('video', 'relatedVideos'));
    }
}
