@extends('layouts.app')

@section('title', 'Beranda')
@section('page-title', 'Beranda')
@section('header-icon', 'fa-home')

@section('content')
    <div class="container-fluid px-3 py-3">

        <!-- Welcome Card -->
        <div class="card border-0" style="background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p class="mb-1 opacity-75" style="font-size: 12px;">Selamat datang,</p>
                        <h3 class="mb-0 fw-bold">{{ auth()->user()->name }}</h3>
                    </div>
                    @if ($ibuHamil && $ibuHamil->foto_kehamilan)
                        <img src="{{ Storage::url($ibuHamil->foto_kehamilan) }}" alt="Foto"
                            class="rounded-circle border border-3 border-white"
                            style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-user-circle fa-3x"></i>
                        </div>
                    @endif
                </div>

                @if ($ibuHamil)
                    <div class="row g-2 pt-3 border-top border-white border-opacity-25">
                        <div class="col-4 text-center">
                            <p class="mb-1 opacity-75" style="font-size: 11px;">Usia Kehamilan</p>
                            <h4 class="mb-0 fw-bold">{{ $usiaKehamilan }}</h4>
                            <small style="font-size: 10px;">Minggu</small>
                        </div>
                        <div class="col-4 text-center">
                            <p class="mb-1 opacity-75" style="font-size: 11px;">Trimester</p>
                            <h4 class="mb-0 fw-bold">{{ $trimester }}</h4>
                            <small style="font-size: 10px;">Trimester {{ $trimester }}</small>
                        </div>
                        <div class="col-4 text-center">
                            <p class="mb-1 opacity-75" style="font-size: 11px;">HPL</p>
                            <h4 class="mb-0 fw-bold">{{ $hariLagi > 0 ? $hariLagi : '0' }}</h4>
                            <small style="font-size: 10px;">Hari lagi</small>
                        </div>
                    </div>
                @else
                    <p class="mb-0 opacity-75" style="font-size: 12px;">Silakan lengkapi data kehamilan Anda di halaman
                        profil</p>
                @endif
            </div>
        </div>

        @if ($isPending)
            <!-- Pending Warning -->
            <div class="alert alert-warning d-flex align-items-start mt-3">
                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                <div>
                    <strong>Akun Menunggu Persetujuan</strong>
                    <p class="mb-0" style="font-size: 12px;">Hubungi puskesmas Anda untuk mempercepat proses verifikasi
                        akun.</p>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <h6 class="section-title mt-4">Menu Cepat</h6>
        <div class="row g-2">
            <div class="col-6">
                <a href="{{ route('app.kesehatan') }}" class="card border-0 h-100 touch-feedback text-decoration-none">
                    <div class="card-body text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 50px; height: 50px; background: #e3f2fd;">
                            <i class="fas fa-clipboard-list fa-lg text-primary"></i>
                        </div>
                        <h6 class="mb-1 fw-bold text-dark">Riwayat Pemeriksaan</h6>
                        <small class="text-muted">{{ $jumlahPemeriksaan }} kali</small>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('app.skrining.create') }}"
                    class="card border-0 h-100 touch-feedback text-decoration-none">
                    <div class="card-body text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 50px; height: 50px; background: #e8f5e9;">
                            <i class="fas fa-check-circle fa-lg" style="color: #4caf50;"></i>
                        </div>
                        <h6 class="mb-1 fw-bold text-dark">Skrining Mandiri</h6>
                        <small class="text-muted">Cek risiko</small>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('app.edukasi') }}" class="card border-0 h-100 touch-feedback text-decoration-none">
                    <div class="card-body text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 50px; height: 50px; background: #f3e5f5;">
                            <i class="fas fa-book-open fa-lg" style="color: #9c27b0;"></i>
                        </div>
                        <h6 class="mb-1 fw-bold text-dark">Edukasi</h6>
                        <small class="text-muted">Artikel & Video</small>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('app.profil') }}" class="card border-0 h-100 touch-feedback text-decoration-none">
                    <div class="card-body text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 50px; height: 50px; background: #fff0f6;">
                            <i class="fas fa-user fa-lg text-primary"></i>
                        </div>
                        <h6 class="mb-1 fw-bold text-dark">Profil Saya</h6>
                        <small class="text-muted">Data diri</small>
                    </div>
                </a>
            </div>
        </div>

        <!-- Tips Harian -->
        <div class="card border-0 mt-3">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                        style="width: 45px; height: 45px; background: #fff9c4;">
                        <i class="fas fa-lightbulb fa-lg" style="color: #fbc02d;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-2">Tips Hari Ini</h6>
                        <p class="mb-0 text-muted" style="font-size: 14px;">{{ $tips }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Pemeriksaan -->
        @if ($latestPemeriksaan)
            <h6 class="section-title mt-4">Pemeriksaan Terakhir</h6>
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Kunjungan ke-{{ $latestPemeriksaan->kunjungan_ke }}</h6>
                        <span
                            class="badge bg-primary">{{ $latestPemeriksaan->tanggal_pemeriksaan->format('d M Y') }}</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Berat Badan</small>
                            <strong>{{ $latestPemeriksaan->berat_badan }} kg</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Tekanan Darah</small>
                            <strong>{{ $latestPemeriksaan->tekanan_darah }}</strong>
                        </div>
                    </div>
                    @if ($latestPemeriksaan->tenagaKesehatan)
                        <small class="text-muted d-block mt-2"><i class="fas fa-user-md me-1"></i>
                            {{ $latestPemeriksaan->tenagaKesehatan->user->nama }}</small>
                    @endif
                </div>
            </div>
        @endif

        <!-- Latest Skrining -->
        @if ($latestSkrining)
            <h6 class="section-title mt-4">Skrining Terakhir</h6>
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">{{ $latestSkrining->tanggal_skrining->format('d M Y') }}</h6>
                        <span
                            class="badge {{ $latestSkrining->kategori_risiko === 'KRR' ? 'badge-success' : ($latestSkrining->kategori_risiko === 'KRT' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $latestSkrining->kategori_risiko }}
                        </span>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Total Skor</small>
                            <strong>{{ $latestSkrining->total_skor }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Jenis</small>
                            <strong>{{ ucfirst($latestSkrining->jenis_skrining) }}</strong>
                        </div>
                    </div>
                    <small class="text-muted d-block"><strong>Rekomendasi:</strong>
                        {{ $latestSkrining->rekomendasi_tempat_bersalin }}</small>
                </div>
            </div>
        @endif

        <!-- Artikel Rekomendasi -->
        @if ($artikelRekomendasi->count() > 0)
            <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                <h6 class="section-title m-0">Artikel untuk Anda</h6>
                <a href="{{ route('app.edukasi') }}" class="text-primary text-decoration-none"
                    style="font-size: 12px;">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            @foreach ($artikelRekomendasi as $artikel)
                <a href="{{ route('app.artikel.show', $artikel->slug) }}"
                    class="card border-0 mb-2 touch-feedback text-decoration-none">
                    <div class="card-body p-2">
                        <div class="d-flex gap-2">
                            @if ($artikel->gambar_utama)
                                <img src="{{ Storage::url($artikel->gambar_utama) }}" alt="{{ $artikel->judul }}"
                                    class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 13px; line-height: 1.4;">
                                    {{ $artikel->judul }}</h6>
                                <small class="text-muted"><i
                                        class="far fa-clock me-1"></i>{{ $artikel->published_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif

    </div>
@endsection
