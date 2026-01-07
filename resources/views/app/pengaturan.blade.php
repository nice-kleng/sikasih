@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('content')
<div class="p-4 space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Notifikasi</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between"><span class="text-sm text-gray-700 dark:text-gray-300">Pemeriksaan</span><input type="checkbox" checked class="w-5 h-5 text-primary-600 rounded"></div>
            <div class="flex items-center justify-between"><span class="text-sm text-gray-700 dark:text-gray-300">Skrining</span><input type="checkbox" checked class="w-5 h-5 text-primary-600 rounded"></div>
            <div class="flex items-center justify-between"><span class="text-sm text-gray-700 dark:text-gray-300">Artikel Baru</span><input type="checkbox" checked class="w-5 h-5 text-primary-600 rounded"></div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Tentang</h3>
        <div class="space-y-2 text-sm"><p class="text-gray-600 dark:text-gray-400">SIKASIH v1.0.0</p><p class="text-gray-600 dark:text-gray-400">Sistem Informasi Kesehatan Ibu Hamil</p></div>
    </div>
</div>
@endsection
