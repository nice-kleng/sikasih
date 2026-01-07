@extends('layouts.app')

@section('title', $video->judul)
@section('page-title', 'Video')

@section('content')
    <div class="p-4 space-y-6">
        <!-- Back Button -->
        <a href="{{ route('app.edukasi', ['type' => 'video']) }}"
            class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 mb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        <!-- Video Player -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <!-- YouTube Video Embed -->
            <div class="relative aspect-video bg-black">
                {{-- @if ($video->youtube_url)
                    @php
                        // Extract YouTube video ID from URL
                        preg_match(
                            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
                            $video->youtube_url,
                            $matches,
                        );
                        $videoId = $matches[1] ?? null;
                    @endphp --}}

                @if ($video->youtube_id)
                    <iframe class="w-full h-full"
                        src="https://www.youtube.com/embed/{{ $video->youtube_id }}?rel=0&modestbranding=1"
                        title="{{ $video->judul }}" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                @else
                    <div class="flex items-center justify-center h-full">
                        <p class="text-white">Video tidak dapat dimuat</p>
                    </div>
                @endif
                {{-- @else
                    <div class="flex items-center justify-center h-full">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                @endif --}}
            </div>

            <div class="p-6">
                <!-- Category Badge & Views -->
                <div class="flex items-center gap-2 mb-3">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                        {{ ucfirst(str_replace('_', ' ', $video->kategori)) }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ number_format($video->views) }} views
                    </span>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $video->judul }}</h1>

                <!-- Meta Info -->
                <div class="flex items-center gap-4 pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $video->published_at->format('d F Y') }}
                    </div>
                    @if ($video->durasi)
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $video->durasi }} menit
                        </div>
                    @endif
                </div>

                <!-- Description -->
                @if ($video->deskripsi)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Deskripsi</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $video->deskripsi }}</p>
                    </div>
                @endif

                <!-- Tags (if exists) -->
                @if ($video->tags)
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach (explode(',', $video->tags) as $tag)
                                <span
                                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm">
                                    #{{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    @if ($video->youtube_url)
                        <a href="{{ $video->youtube_url }}" target="_blank"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                            Tonton di YouTube
                        </a>
                    @endif
                    <button onclick="shareVideo()"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        Share
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Videos -->
        @if ($relatedVideos->count() > 0)
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Video Terkait</h3>
                <div class="space-y-3">
                    @foreach ($relatedVideos as $related)
                        <a href="{{ route('app.video.show', $related->slug) }}"
                            class="block bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow hover:shadow-md transition-shadow">
                            <div class="flex gap-3">
                                @if ($related->thumbnail_url)
                                    <div class="relative w-32 h-24 flex-shrink-0">
                                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->judul }}"
                                            class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                        @if ($related->durasi)
                                            <span
                                                class="absolute bottom-1 right-1 bg-black/80 text-white text-xs px-1.5 py-0.5 rounded">{{ $related->durasi }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div
                                        class="w-32 h-24 bg-gray-200 dark:bg-gray-700 flex-shrink-0 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0 p-3">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm line-clamp-2 mb-1">
                                        {{ $related->judul }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $related->published_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function shareVideo() {
                const title = "{{ $video->judul }}";
                const url = "{{ route('app.video.show', $video->slug) }}";
                const text = `Tonton video menarik: ${title}`;

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
