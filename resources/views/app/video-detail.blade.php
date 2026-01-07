@extends('layouts.app')
@section('title', $video->judul)
@section('page-title', 'Video')
@section('header-icon', 'fa-play-circle')
@section('content')
    <div class="container-fluid px-3 py-3">

        <a href="{{ route('app.edukasi', ['type' => 'video']) }}" class="btn btn-sm btn-outline-primary mb-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>

        <div class="card border-0 shadow-sm">
            <div class="ratio ratio-16x9 bg-dark">
                @if ($video->youtube_url)
                    @php
                        preg_match(
                            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
                            $video->youtube_url,
                            $matches,
                        );
                        $videoId = $matches[1] ?? null;
                    @endphp

                    @if ($videoId)
                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1"
                            title="{{ $video->judul }}" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    @else
                        <div class="d-flex align-items-center justify-content-center">
                            <p class="text-white">Video tidak dapat dimuat</p>
                        </div>
                    @endif
                @else
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-video fa-3x text-secondary"></i>
                    </div>
                @endif
            </div>

            <div class="card-body">
                <span class="badge bg-danger mb-2">
                    <i class="fab fa-youtube me-1"></i>{{ ucfirst(str_replace('_', ' ', $video->kategori)) }}
                </span>

                <h4 class="fw-bold mb-3">{{ $video->judul }}</h4>

                <div class="d-flex gap-3 mb-3 text-muted" style="font-size: 13px;">
                    <span><i class="far fa-calendar me-1"></i>{{ $video->published_at->format('d M Y') }}</span>
                    @if ($video->durasi)
                        <span><i class="far fa-clock me-1"></i>{{ $video->durasi }} menit</span>
                    @endif
                    <span><i class="far fa-eye me-1"></i>{{ number_format($video->views) }} views</span>
                </div>

                @if ($video->deskripsi)
                    <hr>
                    <h6 class="fw-bold">Deskripsi</h6>
                    <p class="text-muted" style="font-size: 14px;">{{ $video->deskripsi }}</p>
                @endif

                @if ($video->tags)
                    <hr>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (explode(',', $video->tags) as $tag)
                            <span class="badge bg-light text-dark">#{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif

                <hr>

                <div class="d-flex gap-2">
                    @if ($video->youtube_url)
                        <a href="{{ $video->youtube_url }}" target="_blank" class="btn btn-danger flex-grow-1">
                            <i class="fab fa-youtube me-2"></i>Tonton di YouTube
                        </a>
                    @endif
                    <button onclick="shareVideo()" class="btn btn-success flex-grow-1">
                        <i class="fab fa-whatsapp me-2"></i>Share
                    </button>
                </div>
            </div>
        </div>

        @if ($relatedVideos->count() > 0)
            <h6 class="section-title mt-4">Video Terkait</h6>
            @foreach ($relatedVideos as $related)
                <a href="{{ route('app.video.show', $related->slug) }}"
                    class="card border-0 shadow-sm mb-2 text-decoration-none">
                    <div class="card-body p-2">
                        <div class="d-flex gap-2">
                            @if ($related->thumbnail_url)
                                <div class="position-relative" style="width: 120px; height: 80px;">
                                    <img src="{{ $related->thumbnail_url }}" alt="{{ $related->judul }}" class="rounded"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <i class="fas fa-play-circle fa-2x text-white"
                                            style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);"></i>
                                    </div>
                                    @if ($related->durasi)
                                        <span
                                            class="position-absolute bottom-0 end-0 m-1 badge bg-dark">{{ $related->durasi }}</span>
                                    @endif
                                </div>
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
            function shareVideo() {
                const title = "{{ $video->judul }}";
                const url = "{{ route('app.video.show', $video->slug) }}";
                const text = `Tonton video: ${title}`;

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
        </script>
    @endpush
@endsection
