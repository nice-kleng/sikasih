<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#ff6b9d">
    <link rel="manifest" href="{{ url('manifest.json') }}">
    <title>SIKASIH - Sistem Informasi Kesehatan Ibu Hamil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffeef8 0%, #fff5f9 100%);
            min-height: 100vh;
            max-width: 480px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding-bottom: 80px;
        }

        .header {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            padding: 15px 20px;
            color: white;
            box-shadow: 0 2px 10px rgba(255, 107, 157, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff6b9d;
            font-size: 20px;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 700;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-auth {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .btn-login:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-register {
            background: white;
            color: #ff6b9d;
        }

        .btn-register:hover {
            background: #f8f9fa;
            color: #ff6b9d;
        }

        .hero {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .hero-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 25px;
        }

        .hero-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-hero {
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-hero-primary {
            background: white;
            color: #ff6b9d;
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }

        .section {
            padding: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .section-link {
            font-size: 13px;
            color: #ff6b9d;
            text-decoration: none;
            font-weight: 600;
        }

        .card-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 15px;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }

        .card-item:hover {
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.2);
            transform: translateY(-2px);
        }

        .card-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #1976d2;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-meta {
            font-size: 12px;
            color: #999;
        }

        .features {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .feature-item {
            display: flex;
            align-items: start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .feature-item:last-child {
            margin-bottom: 0;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ffe8f2 0%, #ffd6e7 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff6b9d;
            font-size: 24px;
            flex-shrink: 0;
        }

        .feature-content h4 {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .feature-content p {
            font-size: 13px;
            color: #666;
            margin: 0;
        }

        .cta-section {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            margin: 20px;
            border-radius: 12px;
        }

        .cta-section h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .cta-section p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .btn-cta {
            background: white;
            color: #ff6b9d;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
        }

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
            padding: 10px 0;
            z-index: 1000;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            color: #999;
            text-decoration: none;
            flex: 1;
            padding: 5px 0;
            transition: all 0.3s;
        }

        .nav-item.active {
            color: #ff6b9d;
        }

        .nav-item i {
            font-size: 20px;
        }

        .nav-item span {
            font-size: 11px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="logo-text">SIKASIH</div>
            </div>
            <div class="auth-buttons">
                <a href="{{ route('app.login') }}" class="btn-auth btn-login">Masuk</a>
                <a href="{{ route('app.register') }}" class="btn-auth btn-register">Daftar</a>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-icon">
            <i class="fas fa-baby"></i>
        </div>
        <h1>Selamat Datang di SIKASIH</h1>
        <p>Sistem Informasi Kesehatan Ibu Hamil<br>Pantau kesehatan kehamilan Anda dengan mudah</p>
        <div class="hero-buttons">
            <a href="{{ route('app.register') }}" class="btn-hero btn-hero-primary">Daftar Sekarang</a>
            <a href="{{ route('app.login') }}" class="btn-hero btn-hero-secondary">Masuk</a>
        </div>
    </div>

    <!-- Artikel Section -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-newspaper me-2"></i>Artikel Terbaru</h2>
            <a href="{{ route('app.artikel.index') }}" class="section-link">Lihat Semua <i
                    class="fas fa-arrow-right ms-1"></i></a>
        </div>

        @forelse($artikels as $artikel)
            <a href="{{ route('app.artikel.show', $artikel->slug) }}" class="card-item">
                @if ($artikel->gambar_utama)
                    <img src="{{ Storage::url($artikel->gambar_utama) }}" alt="{{ $artikel->judul }}" class="card-image"
                        style="height: 150px; object-fit: cover;">
                @else
                    <div class="card-image">
                        <i class="fas fa-newspaper"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h3 class="card-title">{{ $artikel->judul }}</h3>
                    <div class="card-meta">
                        <i class="far fa-calendar me-1"></i>{{ $artikel->published_at->format('d M Y') }}
                        <i class="far fa-eye ms-2 me-1"></i>{{ number_format($artikel->views) }}
                    </div>
                </div>
            </a>
        @empty
            <p class="text-center text-muted">Belum ada artikel</p>
        @endforelse
    </div>

    <!-- Video Section -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-play-circle me-2"></i>Video Edukasi</h2>
            <a href="{{ route('app.video.index') }}" class="section-link">Lihat Semua <i
                    class="fas fa-arrow-right ms-1"></i></a>
        </div>

        @forelse($videos as $video)
            <a href="{{ route('app.video.show', $video->slug) }}" class="card-item">
                @if ($video->thumbnail_url)
                    <div style="position: relative;">
                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->judul }}" class="card-image"
                            style="height: 150px; object-fit: cover;">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <i class="fas fa-play-circle fa-3x text-white"
                                style="text-shadow: 0 2px 8px rgba(0,0,0,0.5);"></i>
                        </div>
                    </div>
                @else
                    <div class="card-image"
                        style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%); color: #7b1fa2;">
                        <i class="fas fa-play-circle"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h3 class="card-title">{{ $video->judul }}</h3>
                    <div class="card-meta">
                        <i class="far fa-calendar me-1"></i>{{ $video->published_at->format('d M Y') }}
                        @if ($video->durasi)
                            <i class="far fa-clock ms-2 me-1"></i>{{ $video->durasi }} menit
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <p class="text-center text-muted">Belum ada video</p>
        @endforelse
    </div>

    <!-- Features Section -->
    <div class="section">
        <h2 class="section-title mb-3"><i class="fas fa-star me-2"></i>Fitur Lengkap untuk Member</h2>
        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="feature-content">
                    <h4>Skrining Risiko Kehamilan</h4>
                    <p>Deteksi dini risiko kehamilan dengan skrining mandiri</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="feature-content">
                    <h4>Riwayat Pemeriksaan ANC</h4>
                    <p>Pantau riwayat pemeriksaan antenatal care lengkap</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="feature-content">
                    <h4>Konsultasi Online</h4>
                    <p>Chat dengan tenaga kesehatan kapan saja</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="feature-content">
                    <h4>Notifikasi Jadwal</h4>
                    <p>Pengingat jadwal pemeriksaan dan kontrol</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h3>Mulai Pantau Kesehatan Anda</h3>
        <p>Daftar sekarang dan nikmati semua fitur gratis!</p>
        <a href="{{ route('app.register') }}" class="btn-cta">
            <i class="fas fa-user-plus me-2"></i>Daftar Gratis Sekarang
        </a>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="{{ route('app.home') }}" class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('app.artikel.index') }}" class="nav-item">
            <i class="fas fa-newspaper"></i>
            <span>Artikel</span>
        </a>
        <a href="{{ route('app.video.index') }}" class="nav-item">
            <i class="fas fa-play-circle"></i>
            <span>Video</span>
        </a>
        <a href="{{ route('app.login') }}" class="nav-item">
            <i class="fas fa-sign-in-alt"></i>
            <span>Masuk</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
