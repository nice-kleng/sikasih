<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#EC4899">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SIKASIH">

    <!-- PWA Links -->
    <link rel="manifest" href="{{ url('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.png') }}">

    <title>@yield('title', 'SIKASIH') - Kesehatan Ibu Hamil</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Safe area for notch devices */
        body {
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Bottom nav safe area */
        .bottom-nav {
            padding-bottom: calc(env(safe-area-inset-bottom) + 0.5rem);
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Touch feedback */
        .touch-feedback:active {
            transform: scale(0.95);
            transition: transform 0.1s;
        }

        /* Skeleton loading */
        .skeleton {
            animation: skeleton-loading 1s linear infinite alternate;
        }

        @keyframes skeleton-loading {
            0% {
                background-color: hsl(200, 20%, 80%);
            }

            100% {
                background-color: hsl(200, 20%, 95%);
            }
        }

        /* Pull to refresh indicator */
        .pull-to-refresh {
            transform: translateY(-100%);
            transition: transform 0.3s;
        }

        .pull-to-refresh.visible {
            transform: translateY(0);
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#FDF2F8',
                            100: '#FCE7F3',
                            200: '#FBCFE8',
                            300: '#F9A8D4',
                            400: '#F472B6',
                            500: '#EC4899',
                            600: '#DB2777',
                            700: '#BE185D',
                            800: '#9D174D',
                            900: '#831843',
                        }
                    }
                }
            }
        }
    </script>

    @stack('styles')
</head>

<body class="bg-primary-50 dark:bg-gray-900" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }">

    <!-- Install PWA Banner -->
    <div x-data="{ showInstall: false, deferredPrompt: null }"
        @beforeinstallprompt.window="deferredPrompt = $event; $event.preventDefault(); showInstall = true"
        x-show="showInstall" x-cloak class="fixed top-0 left-0 right-0 z-50 bg-primary-500 text-white p-4 shadow-lg">
        <div class="flex items-center justify-between max-w-md mx-auto">
            <div class="flex-1">
                <p class="font-semibold">Install SIKASIH</p>
                <p class="text-sm text-primary-100">Akses lebih cepat dari home screen</p>
            </div>
            <div class="flex gap-2 ml-4">
                <button @click="showInstall = false" class="px-3 py-1 text-sm bg-white/20 rounded-lg">
                    Nanti
                </button>
                <button @click="deferredPrompt.prompt(); deferredPrompt.userChoice.then(() => { showInstall = false; })"
                    class="px-3 py-1 text-sm bg-white text-primary-600 rounded-lg font-semibold">
                    Install
                </button>
            </div>
        </div>
    </div>

    <!-- Top Bar -->
    <header
        class="fixed top-0 left-0 right-0 z-40 bg-primary-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SIKASIH" class="w-8 h-8"
                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22%23EC4899%22%3E%3Cpath d=%22M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z%22/%3E%3C/svg%3E'">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">@yield('page-title', 'SIKASIH')</h1>
            </div>
            <div class="flex items-center gap-2">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 touch-feedback">
                    <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                <!-- Notifications (if not on notifikasi page) -->
                @if (!request()->routeIs('app.notifikasi'))
                    <a href="{{ route('app.notifikasi') }}"
                        class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 touch-feedback">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-16 pb-20 min-h-screen">
        <!-- Pending Account Banner -->
        @if (auth()->check() && auth()->user()->status === 'pending')
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-b border-yellow-200 dark:border-yellow-800 p-4">
                <div class="max-w-md mx-auto flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">Akun Menunggu Persetujuan
                        </p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Akun Anda sedang ditinjau oleh
                            admin puskesmas. Beberapa fitur terbatas hingga akun disetujui.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Flash Messages -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-md mx-auto mt-4 px-4">
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-md mx-auto mt-4 px-4">
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <div class="max-w-md mx-auto">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav
        class="fixed bottom-0 left-0 right-0 bg-primary-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 bottom-nav z-40">
        <div class="max-w-md mx-auto px-2 py-2">
            <div class="flex items-center justify-around">
                <!-- Beranda -->
                <a href="{{ route('app.beranda') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg touch-feedback {{ request()->routeIs('app.beranda') ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-xs font-medium">Beranda</span>
                </a>

                <!-- Kesehatan -->
                <a href="{{ route('app.kesehatan') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg touch-feedback {{ request()->routeIs('app.kesehatan*') || request()->routeIs('app.skrining*') ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="text-xs font-medium">Kesehatan</span>
                </a>

                <!-- Edukasi -->
                <a href="{{ route('app.edukasi') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg touch-feedback {{ request()->routeIs('app.edukasi*') || request()->routeIs('app.artikel*') || request()->routeIs('app.video*') ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-xs font-medium">Edukasi</span>
                </a>

                <!-- Notifikasi -->
                <a href="{{ route('app.notifikasi') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg touch-feedback relative {{ request()->routeIs('app.notifikasi') ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="text-xs font-medium">Notifikasi</span>
                    <span class="absolute top-1 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                </a>

                <!-- Profil -->
                <a href="{{ route('app.profil') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg touch-feedback {{ request()->routeIs('app.profil') || request()->routeIs('app.pengaturan') ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-xs font-medium">Profil</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered'))
                    .catch(err => console.log('Service Worker registration failed'));
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
