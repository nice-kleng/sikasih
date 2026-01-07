@extends('layouts.app')

@section('title', $artikel->judul)
@section('page-title', 'Artikel')

@section('content')
    <div class="p-4 space-y-6">
        <!-- Back Button -->
        <a href="{{ route('app.edukasi', ['type' => 'artikel']) }}"
            class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 mb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        <!-- Article Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            @if ($artikel->gambar_utama)
                <img src="{{ Storage::url($artikel->gambar_utama) }}" alt="{{ $artikel->judul }}"
                    class="w-full h-64 object-cover">
            @else
                <div
                    class="w-full h-64 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900 dark:to-primary-800 flex items-center justify-center">
                    <svg class="w-20 h-20 text-primary-400 dark:text-primary-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            @endif

            <div class="p-6">
                <!-- Category Badge -->
                <div class="flex items-center gap-2 mb-3">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                        {{ ucfirst(str_replace('_', ' ', $artikel->kategori)) }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ number_format($artikel->views) }} views
                    </span>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $artikel->judul }}</h1>

                <!-- Meta Info -->
                <div class="flex items-center gap-4 pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $artikel->published_at->format('d F Y') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $artikel->reading_time ?? '5' }} menit baca
                    </div>
                </div>

                <!-- Content -->
                <div class="prose prose-pink dark:prose-invert max-w-none">
                    {!! nl2br(e($artikel->konten)) !!}
                </div>

                <!-- Tags (if exists) -->
                @if ($artikel->tags)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap gap-2">
                            @foreach (explode(',', $artikel->tags) as $tag)
                                <span
                                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm">
                                    #{{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Share Buttons -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Bagikan artikel ini:</p>
                    <div class="flex gap-3">
                        <button onclick="shareArticle()"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            WhatsApp
                        </button>
                        <button onclick="copyLink()"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Articles -->
        @if ($relatedArtikels->count() > 0)
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Artikel Terkait</h3>
                <div class="space-y-3">
                    @foreach ($relatedArtikels as $related)
                        <a href="{{ route('app.artikel.show', $related->slug) }}"
                            class="block bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-md transition-shadow">
                            <div class="flex gap-3">
                                @if ($related->gambar_utama)
                                    <img src="{{ Storage::url($related->gambar_utama) }}" alt="{{ $related->judul }}"
                                        class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-20 h-20 rounded-lg bg-gray-200 dark:bg-gray-700 flex-shrink-0"></div>
                                @endif
                                <div class="flex-1 min-w-0">
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
            function shareArticle() {
                const title = "{{ $artikel->judul }}";
                const url = "{{ route('app.artikel.show', $artikel->slug) }}";
                const text = `Baca artikel menarik: ${title}`;

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
