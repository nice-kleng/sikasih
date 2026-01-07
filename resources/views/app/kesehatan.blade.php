@extends('layouts.app')
@section('title', 'Kesehatan')
@section('page-title', 'Kesehatan Saya')
@section('content')
    <div class="p-4" x-data="{ activeTab: 'pemeriksaan' }">
        <div class="flex gap-2 mb-6">
            <button @click="activeTab = 'pemeriksaan'"
                :class="activeTab === 'pemeriksaan' ? 'bg-primary-500 text-white' :
                    'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
                class="flex-1 py-3 rounded-lg font-semibold transition-colors">
                Pemeriksaan ANC
            </button>
            <button @click="activeTab = 'skrining'"
                :class="activeTab === 'skrining' ? 'bg-primary-500 text-white' :
                    'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
                class="flex-1 py-3 rounded-lg font-semibold transition-colors">
                Skrining Risiko
            </button>
        </div>
        <div x-show="activeTab === 'pemeriksaan'" class="space-y-4">
            @forelse($pemeriksaan as $p)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Kunjungan ke-{{ $p->kunjungan_ke }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $p->tanggal_pemeriksaan->format('d F Y') }}</p>
                        </div>
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            UK: {{ $p->usia_kehamilan_minggu }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">BB</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $p->berat_badan }} kg</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">TD</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $p->tekanan_darah }}</p>
                        </div>
                    </div>
                    @if ($p->tenagaKesehatan)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Pemeriksa:
                            {{ $p->tenagaKesehatan->user->nama }}</p>
                    @endif
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat pemeriksaan</p>
                </div>
            @endforelse
        </div>
        <div x-show="activeTab === 'skrining'" class="space-y-4">
            <a href="{{ route('app.skrining.create') }}"
                class="block bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg mb-1">Skrining Mandiri</h3>
                        <p class="text-sm text-primary-100">Cek risiko kehamilan Anda</p>
                    </div>
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            </a>
            @forelse($skrining as $s)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Skrining
                                {{ ucfirst($s->jenis_skrining) }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $s->tanggal_skrining->format('d F Y') }}
                            </p>
                        </div>
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded-full
                    {{ $s->kategori_risiko === 'KRR' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $s->kategori_risiko === 'KRT' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $s->kategori_risiko === 'KRST' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $s->kategori_risiko }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Skor</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $s->total_skor }}</span>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 mb-1">Rekomendasi:</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $s->rekomendasi_tempat_bersalin }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat skrining</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
