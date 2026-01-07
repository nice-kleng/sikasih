@extends('layouts.app')

@section('title', 'Beranda')
@section('page-title', 'Beranda')

@section('content')
    <div class="p-4 space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-primary-100 text-sm mb-1">Selamat datang,</p>
                    <h2 class="text-2xl font-bold">{{ auth()->user()->name }}</h2>
                </div>
                @if ($ibuHamil && $ibuHamil->foto_kehamilan)
                    <img src="{{ Storage::url($ibuHamil->foto_kehamilan) }}" alt="Foto"
                        class="w-16 h-16 rounded-full border-4 border-white/30">
                @else
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                        </svg>
                    </div>
                @endif
            </div>

            @if ($ibuHamil)
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-white/20">
                    <div>
                        <p class="text-primary-100 text-xs mb-1">Usia Kehamilan</p>
                        <p class="text-xl font-bold">{{ $usiaKehamilan }} Minggu</p>
                    </div>
                    <div>
                        <p class="text-primary-100 text-xs mb-1">Trimester</p>
                        <p class="text-xl font-bold">{{ $trimester }}</p>
                    </div>
                    <div>
                        <p class="text-primary-100 text-xs mb-1">HPL</p>
                        <p class="text-xl font-bold">{{ $hariLagi > 0 ? $hariLagi . ' Hari' : 'Sudah HPL' }}</p>
                    </div>
                </div>
            @else
                <p class="text-primary-100 text-sm">Silakan lengkapi data kehamilan Anda di halaman profil</p>
            @endif
        </div>

        @if ($isPending)
            <!-- Pending Warning -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">Akun Menunggu Persetujuan</p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Hubungi puskesmas Anda untuk
                            mempercepat proses verifikasi akun.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('app.kesehatan') }}"
                class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-md transition-shadow touch-feedback">
                <div class="flex flex-col items-center text-center">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Riwayat Pemeriksaan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $jumlahPemeriksaan }} kali</p>
                </div>
            </a>

            <a href="{{ route('app.skrining.create') }}"
                class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-md transition-shadow touch-feedback">
                <div class="flex flex-col items-center text-center">
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Skrining Mandiri</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cek risiko</p>
                </div>
            </a>

            <a href="{{ route('app.edukasi') }}"
                class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-md transition-shadow touch-feedback">
                <div class="flex flex-col items-center text-center">
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Edukasi</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Artikel & Video</p>
                </div>
            </a>

            <a href="{{ route('app.profil') }}"
                class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-md transition-shadow touch-feedback">
                <div class="flex flex-col items-center text-center">
                    <div
                        class="w-12 h-12 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Profil Saya</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Data diri</p>
                </div>
            </a>
        </div>

        <!-- Tips Harian -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
            <div class="flex items-start gap-3 mb-3">
                <div
                    class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Tips Hari Ini</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $tips }}</p>
                </div>
            </div>
        </div>

        <!-- Latest Pemeriksaan -->
        @if ($latestPemeriksaan)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Pemeriksaan Terakhir</h3>
                    <a href="{{ route('app.kesehatan') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat
                        Semua</a>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Tanggal</span>
                        <span
                            class="font-medium text-gray-900 dark:text-white">{{ $latestPemeriksaan->tanggal_pemeriksaan->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Kunjungan Ke</span>
                        <span
                            class="font-medium text-gray-900 dark:text-white">{{ $latestPemeriksaan->kunjungan_ke }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Berat Badan</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $latestPemeriksaan->berat_badan }}
                            kg</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Tekanan Darah</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $latestPemeriksaan->tekanan_darah }}
                            mmHg</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Latest Skrining -->
        @if ($latestSkrining)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Skrining Terakhir</h3>
                    <a href="{{ route('app.kesehatan') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat
                        Semua</a>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Tanggal</span>
                        <span
                            class="font-medium text-gray-900 dark:text-white">{{ $latestSkrining->tanggal_skrining->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Total Skor</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $latestSkrining->total_skor }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Kategori Risiko</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                    {{ $latestSkrining->kategori_risiko === 'KRR' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                    {{ $latestSkrining->kategori_risiko === 'KRT' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                    {{ $latestSkrining->kategori_risiko === 'KRST' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                            {{ $latestSkrining->kategori_risiko }}
                        </span>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-600 dark:text-gray-400 mb-1">Rekomendasi:</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $latestSkrining->rekomendasi_tempat_bersalin }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Artikel Rekomendasi -->
        @if ($artikelRekomendasi->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Artikel untuk Anda</h3>
                    <a href="{{ route('app.edukasi') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat
                        Semua</a>
                </div>
                <div class="space-y-3">
                    @foreach ($artikelRekomendasi as $artikel)
                        <a href="{{ route('app.artikel.show', $artikel->slug) }}"
                            class="block bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-md transition-shadow">
                            <div class="flex gap-3">
                                @if ($artikel->gambar_utama)
                                    <img src="{{ Storage::url($artikel->gambar_utama) }}" alt="{{ $artikel->judul }}"
                                        class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-20 h-20 rounded-lg bg-gray-200 dark:bg-gray-700 flex-shrink-0"></div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm line-clamp-2 mb-1">
                                        {{ $artikel->judul }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $artikel->published_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
