@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('content')
<div class="p-4 space-y-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
        <div class="flex gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="flex-1"><h3 class="font-semibold text-gray-900 dark:text-white mb-1">Selamat datang di SIKASIH!</h3><p class="text-sm text-gray-600 dark:text-gray-400">Terima kasih telah mendaftar. Lengkapi profil Anda untuk pengalaman yang lebih baik.</p><p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Hari ini</p></div>
        </div>
    </div>
    <div class="text-center py-12"><svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg><p class="text-gray-500 dark:text-gray-400">Belum ada notifikasi lain</p></div>
</div>
@endsection
