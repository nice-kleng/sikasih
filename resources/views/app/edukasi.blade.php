@extends('layouts.app')
@section('title', 'Edukasi')
@section('page-title', 'Edukasi')
@section('content')
    <div class="p-4 space-y-6">
        <div class="flex gap-2 mb-4">
            <a href="{{ route('app.edukasi', ['type' => 'artikel']) }}"
                class="flex-1 py-2 text-center rounded-lg font-semibold {{ $type === 'artikel' ? 'bg-primary-500 text-white' : 'bg-white text-gray-700' }}">
                Artikel
            </a>
            <a href="{{ route('app.edukasi', ['type' => 'video']) }}"
                class="flex-1 py-2 text-center rounded-lg font-semibold {{ $type === 'video' ? 'bg-primary-500 text-white' : 'bg-white text-gray-700' }}">
                Video
            </a>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari..."
                class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
            <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
        <div class="flex gap-2 overflow-x-auto pb-2">
            @foreach ($kategoris as $key => $label)
                <a href="{{ route('app.edukasi', ['type' => $type, 'kategori' => $key]) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $kategori === $key ? 'bg-primary-500 text-white' : 'bg-white text-gray-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <div class="grid grid-cols-1 gap-4">
            @forelse($items as $item)
                <a href="{{ $type === 'artikel' ? route('app.artikel.show', $item->slug) : route('app.video.show', $item->slug) }}"
                    class="bg-white rounded-xl shadow hover:shadow-md transition-shadow overflow-hidden">
                    @if ($type === 'artikel' && $item->gambar_utama)
                        <img src="{{ Storage::url($item->gambar_utama) }}" alt="{{ $item->judul }}"
                            class="w-full h-48 object-cover">
                    @elseif($type === 'video' && $item->thumbnail)
                        <img src="{{ $item->thumbnail }}" alt="{{ $item->judul }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 line-clamp-2">{{ $item->judul }}</h3>
                        <p class="text-sm text-gray-500 mt-2">{{ $item->published_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <p class="text-center text-gray-500 py-12">Tidak ada {{ $type }}</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $items->links() }}</div>
    </div>
@endsection
