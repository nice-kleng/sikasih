<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#ff6b9d">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SIKASIH">

    <!-- PWA Links -->
    <link rel="manifest" href="{{ url('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.png') }}">

    <title>@yield('title', 'SIKASIH') - Kesehatan Ibu Hamil</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffeef8 0%, #fff5f9 100%);
            min-height: 100vh;
            max-width: 480px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Header Styles */
        .app-header {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            padding: 15px 20px;
            color: white;
            box-shadow: 0 2px 10px rgba(255, 107, 157, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .app-header h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            text-align: center;
        }

        .app-header .subtitle {
            font-size: 12px;
            margin: 5px 0 0 0;
            text-align: center;
            opacity: 0.95;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 180px);
            padding-bottom: 80px;
        }

        /* Install PWA Banner */
        .install-banner {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            padding: 12px 15px;
            box-shadow: 0 2px 10px rgba(255, 107, 157, 0.3);
        }

        /* Pending Account Banner */
        .pending-banner {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            margin: 0;
        }

        /* Alert Messages */
        .alert {
            border: none;
            border-radius: 8px;
            margin: 15px;
        }

        .alert-success {
            background-color: #d1f4e0;
            color: #0d6832;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            max-width: 480px;
            width: 100%;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
            z-index: 1000;
        }

        .nav-item {
            text-align: center;
            color: #999;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            flex: 1;
            padding: 5px;
        }

        .nav-item:hover {
            color: #ff6b9d;
            text-decoration: none;
        }

        .nav-item.active {
            color: #ff6b9d;
        }

        .nav-item i {
            font-size: 22px;
            display: block;
            margin-bottom: 3px;
        }

        .nav-item span {
            font-size: 10px;
            display: block;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.2);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            border: none;
            border-radius: 12px 12px 0 0 !important;
            padding: 12px 15px;
            font-weight: 600;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff4783 0%, #ff6b9d 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 157, 0.4);
        }

        .btn-outline-primary {
            color: #ff6b9d;
            border: 2px solid #ff6b9d;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-primary:hover {
            background: #ff6b9d;
            border-color: #ff6b9d;
            color: white;
        }

        /* Forms */
        .form-control,
        .form-select {
            border: 1px solid #ffcce0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #ff6b9d;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 11px;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        /* Utilities */
        .text-primary {
            color: #ff6b9d !important;
        }

        .bg-primary {
            background-color: #ff6b9d !important;
        }

        .border-primary {
            border-color: #ff6b9d !important;
        }

        /* Loading Skeleton */
        .skeleton {
            animation: skeleton-loading 1s linear infinite alternate;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            border-radius: 4px;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Touch Feedback */
        .touch-feedback:active {
            transform: scale(0.95);
            transition: transform 0.1s;
        }

        /* Section Title */
        .section-title {
            color: #ff6b9d;
            font-size: 18px;
            font-weight: 700;
            margin: 20px 0 15px 0;
            padding-left: 10px;
            border-left: 4px solid #ff6b9d;
        }

        /* List Group */
        .list-group-item {
            border: none;
            border-left: 4px solid transparent;
            margin-bottom: 8px;
            border-radius: 8px !important;
            transition: all 0.3s;
        }

        .list-group-item:hover {
            border-left-color: #ff6b9d;
            background-color: #fff0f6;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Responsive Images */
        img {
            max-width: 100%;
            height: auto;
        }

        @stack('styles')
    </style>
</head>

<body>

    <!-- Install PWA Banner -->
    <div x-data="{ showInstall: false, deferredPrompt: null }"
        @beforeinstallprompt.window="deferredPrompt = $event; $event.preventDefault(); showInstall = true"
        x-show="showInstall" x-cloak class="install-banner">
        <div class="d-flex align-items-center justify-content-between">
            <div class="flex-grow-1">
                <strong>Install SIKASIH</strong>
                <p class="mb-0" style="font-size: 11px;">Akses lebih cepat dari home screen</p>
            </div>
            <div class="d-flex gap-2">
                <button @click="showInstall = false" class="btn btn-sm btn-light">
                    Nanti
                </button>
                <button @click="deferredPrompt.prompt(); deferredPrompt.userChoice.then(() => { showInstall = false; })"
                    class="btn btn-sm btn-light fw-bold">
                    Install
                </button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="app-header">
        <h1><i class="fas @yield('header-icon', 'fa-home')"></i> @yield('page-title', 'SIKASIH')</h1>
        @if (isset($subtitle))
            <p class="subtitle">{{ $subtitle }}</p>
        @endif
    </header>

    <!-- Pending Account Banner -->
    @if (auth()->check() && auth()->user()->status === 'pending')
        <div class="pending-banner">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle text-warning me-2 mt-1"></i>
                <div class="flex-grow-1">
                    <strong class="text-warning">Akun Menunggu Persetujuan</strong>
                    <p class="mb-0" style="font-size: 12px;">Akun Anda sedang ditinjau oleh admin puskesmas. Beberapa
                        fitur terbatas hingga akun disetujui.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert" x-data="{ show: true }"
            x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" @click="show = false"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert" x-data="{ show: true }"
            x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" @click="show = false"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show m-3" role="alert" x-data="{ show: true }"
            x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Perhatian!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" @click="show = false"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ route('app.beranda') }}" class="nav-item {{ request()->routeIs('app.beranda') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('app.kesehatan') }}"
            class="nav-item {{ request()->routeIs('app.kesehatan*') || request()->routeIs('app.skrining*') ? 'active' : '' }}">
            <i class="fas fa-heartbeat"></i>
            <span>Kesehatan</span>
        </a>
        <a href="{{ route('app.edukasi') }}"
            class="nav-item {{ request()->routeIs('app.edukasi*') || request()->routeIs('app.artikel*') || request()->routeIs('app.video*') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i>
            <span>Edukasi</span>
        </a>
        <a href="{{ route('app.notifikasi') }}"
            class="nav-item {{ request()->routeIs('app.notifikasi') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span>Notifikasi</span>
        </a>
        <a href="{{ route('app.profil') }}"
            class="nav-item {{ request()->routeIs('app.profil') || request()->routeIs('app.pengaturan') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </a>
    </nav>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
