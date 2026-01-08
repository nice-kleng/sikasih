@extends('layouts.app')
@section('title', 'Artikel')
@section('page-title', 'Artikel Kesehatan')
@section('header-icon', 'fa-newspaper')
@section('content')
    <div class="container-fluid px-3 py-3">

        <!-- Search & Filter -->
        <form method="GET" class="mb-3">
            <div class="input-group mb-2">
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Cari artikel...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Category Filter -->
        <div class="d-flex gap-2 overflow-auto pb-2 mb-3">
            @foreach ($kategoris as $key => $label)
                <a href="{{ route('app.artikel.index', ['kategori' => $key]) }}"
                    class="btn btn-sm {{ $kategori === $key ? 'btn-primary' : 'btn-outline-secondary' }} text-nowrap">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Articles List -->
        @forelse($artikels as $artikel)
            <a href="{{ route('app.artikel.show', $artikel->slug) }}"
                class="card border-0 shadow-sm mb-3 text-decoration-none">
                <div class="card-body p-2">
                    <div class="d-flex gap-2">
                        @if ($artikel->gambar_utama)
                            <img src="{{ Storage::url($artikel->gambar_utama) }}" alt="{{ $artikel->judul }}"
                                class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                style="width: 100px; height: 100px;">
                                <i class="fas fa-newspaper fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 14px;">{{ $artikel->judul }}</h6>
                            <small class="text-muted d-block mb-1">
                                <i class="far fa-clock me-1"></i>{{ $artikel->published_at->diffForHumans() }}
                            </small>
                            <small class="text-muted">
                                <i class="far fa-eye me-1"></i>{{ number_format($artikel->views) }} views
                            </small>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada artikel ditemukan</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if ($artikels->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $artikels->links('pagination::bootstrap-5') }}
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
