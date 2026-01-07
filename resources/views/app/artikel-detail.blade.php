@extends('layouts.app')
@section('title', $artikel->judul)
@section('page-title', 'Artikel')
@section('header-icon', 'fa-newspaper')
@section('content')
    <div class="container-fluid px-3 py-3">

        <a href="{{ route('app.edukasi', ['type' => 'artikel']) }}" class="btn btn-sm btn-outline-primary mb-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>

        <div class="card border-0 shadow-sm">
            @if ($artikel->gambar_utama)
                <img src="{{ Storage::url($artikel->gambar_utama) }}" alt="{{ $artikel->judul }}" class="card-img-top"
                    style="height: 200px; object-fit: cover;">
            @endif

            <div class="card-body">
                <span class="badge bg-primary mb-2">{{ ucfirst(str_replace('_', ' ', $artikel->kategori)) }}</span>

                <h4 class="fw-bold mb-3">{{ $artikel->judul }}</h4>

                <div class="d-flex gap-3 mb-3 text-muted" style="font-size: 13px;">
                    <span><i class="far fa-calendar me-1"></i>{{ $artikel->published_at->format('d M Y') }}</span>
                    <span><i class="far fa-clock me-1"></i>{{ $artikel->reading_time ?? '5' }} menit</span>
                    <span><i class="far fa-eye me-1"></i>{{ number_format($artikel->views) }} views</span>
                </div>

                <hr>

                <div class="article-content" style="line-height: 1.8;">
                    {!! nl2br(e($artikel->konten)) !!}
                </div>

                @if ($artikel->tags)
                    <hr>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (explode(',', $artikel->tags) as $tag)
                            <span class="badge bg-light text-dark">#{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif

                <hr>

                <p class="fw-bold mb-2"><i class="fas fa-share-alt me-2"></i>Bagikan artikel:</p>
                <div class="d-flex gap-2">
                    <button onclick="shareArticle()" class="btn btn-success flex-grow-1">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </button>
                    <button onclick="copyLink()" class="btn btn-secondary flex-grow-1">
                        <i class="fas fa-link me-2"></i>Copy Link
                    </button>
                </div>
            </div>
        </div>

        @if ($relatedArtikels->count() > 0)
            <h6 class="section-title mt-4">Artikel Terkait</h6>
            @foreach ($relatedArtikels as $related)
                <a href="{{ route('app.artikel.show', $related->slug) }}"
                    class="card border-0 shadow-sm mb-2 text-decoration-none">
                    <div class="card-body p-2">
                        <div class="d-flex gap-2">
                            @if ($related->gambar_utama)
                                <img src="{{ Storage::url($related->gambar_utama) }}" alt="{{ $related->judul }}"
                                    class="rounded" style="width: 70px; height: 70px; object-fit: cover;">
                            @else
                                <div class="rounded bg-light" style="width: 70px; height: 70px;"></div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 13px;">{{ $related->judul }}</h6>
                                <small class="text-muted">{{ $related->published_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif

    </div>

    @push('scripts')
        <script>
            function shareArticle() {
                const title = "{{ $artikel->judul }}";
                const url = "{{ route('app.artikel.show', $artikel->slug) }}";
                const text = `Baca artikel: ${title}`;

                if (navigator.share) {
                    navigator.share({
                        title,
                        text,
                        url
                    });
                } else {
                    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
                    window.open(whatsappUrl, '_blank');
                }
            }

            function copyLink() {
                const url = "{{ route('app.artikel.show', $artikel->slug) }}";
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link berhasil disalin!');
                });
            }
        </script>
    @endpush
@endsection
