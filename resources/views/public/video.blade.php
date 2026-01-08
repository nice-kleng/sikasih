@extends('layouts.app')
@section('title', 'Video')
@section('page-title', 'Video Edukasi')
@section('header-icon', 'fa-play-circle')
@section('content')
    <div class="container-fluid px-3 py-3">

        <!-- Search & Filter -->
        <form method="GET" class="mb-3">
            <div class="input-group mb-2">
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Cari video...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Category Filter -->
        <div class="d-flex gap-2 overflow-auto pb-2 mb-3">
            @foreach ($kategoris as $key => $label)
                <a href="{{ route('app.video.index', ['kategori' => $key]) }}"
                    class="btn btn-sm {{ $kategori === $key ? 'btn-primary' : 'btn-outline-secondary' }} text-nowrap">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Videos List -->
        @forelse($videos as $video)
            <a href="{{ route('app.video.show', $video->slug) }}" class="card border-0 shadow-sm mb-3 text-decoration-none">
                <div class="card-body p-2">
                    <div class="d-flex gap-2">
                        @if ($video->thumbnail_url)
                            <div class="position-relative" style="width: 120px; height: 80px;">
                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->judul }}" class="rounded"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <i class="fas fa-play-circle fa-2x text-white"
                                        style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);"></i>
                                </div>
                                @if ($video->durasi)
                                    <span
                                        class="position-absolute bottom-0 end-0 m-1 badge bg-dark">{{ $video->durasi }}</span>
                                @endif
                            </div>
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                style="width: 120px; height: 80px;">
                                <i class="fas fa-video fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 14px;">{{ $video->judul }}</h6>
                            <small class="text-muted d-block mb-1">
                                <i class="far fa-clock me-1"></i>{{ $video->published_at->diffForHumans() }}
                            </small>
                            <small class="text-muted">
                                <i class="far fa-eye me-1"></i>{{ number_format($video->views) }} views
                            </small>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada video ditemukan</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if ($videos->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $videos->links('pagination::bootstrap-5') }}
            </div>
        @endif

        @guest
            <!-- CTA for Guest Users -->
            <div class="card border-0 shadow-sm mt-4 mb-3"
                style="background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);">
                <div class="card-body text-white text-center p-4">
                    <h5 class="fw-bold mb-2">Ingin Akses Fitur Lengkap?</h5>
                    <p class="mb-3" style="font-size: 14px;">Daftar sekarang dan pantau kesehatan kehamilan Anda!</p>
                    <a href="{{ route('app.register') }}" class="btn btn-light text-primary fw-bold">
                        <i class="fas fa-user-plus me-2"></i>Daftar Gratis
                    </a>
                </div>
            </div>
        @endguest

    </div>
@endsection
