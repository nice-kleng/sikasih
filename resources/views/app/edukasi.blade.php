@extends('layouts.app')
@section('title', 'Edukasi')
@section('page-title', 'Edukasi')
@section('header-icon', 'fa-book-open')
@section('content')
    <div class="container-fluid px-3 py-3">

        <!-- Type Toggle -->
        <div class="btn-group w-100 mb-3" role="group">
            <a href="{{ route('app.edukasi', ['type' => 'artikel']) }}"
                class="btn {{ $type === 'artikel' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="fas fa-newspaper me-1"></i>Artikel
            </a>
            <a href="{{ route('app.edukasi', ['type' => 'video']) }}"
                class="btn {{ $type === 'video' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="fas fa-play-circle me-1"></i>Video
            </a>
        </div>

        <!-- Search -->
        <form method="GET" class="mb-3">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="input-group">
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Cari {{ $type }}...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Category Filter -->
        <div class="d-flex gap-2 overflow-auto pb-2 mb-3">
            @foreach ($kategoris as $key => $label)
                <a href="{{ route('app.edukasi', ['type' => $type, 'kategori' => $key]) }}"
                    class="btn btn-sm {{ $kategori === $key ? 'btn-primary' : 'btn-outline-secondary' }} text-nowrap">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Items List -->
        @forelse($items as $item)
            <a href="{{ $type === 'artikel' ? route('app.artikel.show', $item->slug) : route('app.video.show', $item->slug) }}"
                class="card border-0 shadow-sm mb-3 text-decoration-none">
                <div class="card-body p-2">
                    <div class="d-flex gap-2">
                        @if ($type === 'artikel' && $item->gambar_utama)
                            <img src="{{ Storage::url($item->gambar_utama) }}" alt="{{ $item->judul }}" class="rounded"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        @elseif($type === 'video' && $item->thumbnail_url)
                            <div class="position-relative" style="width: 100px; height: 100px;">
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->judul }}" class="rounded"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <i class="fas fa-play-circle fa-2x text-white"
                                        style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);"></i>
                                </div>
                            </div>
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                style="width: 100px; height: 100px;">
                                <i
                                    class="fas {{ $type === 'artikel' ? 'fa-newspaper' : 'fa-video' }} fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 14px;">{{ $item->judul }}</h6>
                            <small class="text-muted"><i
                                    class="far fa-clock me-1"></i>{{ $item->published_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada {{ $type }} ditemukan</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if ($items->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
@endsection
