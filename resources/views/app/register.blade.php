<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#ff6b9d">
    <link rel="manifest" href="{{ url('manifest.json') }}">
    <title>Daftar - SIKASIH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffeef8 0%, #fff5f9 100%);
            min-height: 100vh;
            max-width: 480px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            padding: 15px 20px;
            color: white;
            box-shadow: 0 2px 10px rgba(255, 107, 157, 0.3);
        }

        .form-control,
        .form-select {
            border: 1px solid #ffcce0;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #ff6b9d;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff4783 0%, #ff6b9d 100%);
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="d-flex align-items-center">
            <a href="{{ route('app.login') }}" class="text-white text-decoration-none me-3">
                <i class="fas fa-arrow-left fa-lg"></i>
            </a>
            <div>
                <h1 class="mb-0 fs-5 fw-bold">Daftar Akun Baru</h1>
                <p class="mb-0 opacity-75" style="font-size: 12px;">Lengkapi data diri Anda</p>
            </div>
        </div>
    </div>

    <div class="container-fluid p-3" x-data="{ showPassword: false }">
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('app.register.post') }}">
            @csrf

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"
                    style="background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%); color: white;">
                    <i class="fas fa-user me-2"></i>Data Pribadi
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap *</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                            placeholder="Contoh: Siti Nurhaliza" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">NIK (16 digit) *</label>
                        <input type="text" name="nik" class="form-control" value="{{ old('nik') }}"
                            maxlength="16" pattern="[0-9]{16}" placeholder="3578125505950001" required>
                        <small class="text-muted">Sesuai KTP</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">No. Telepon/WhatsApp *</label>
                        <input type="tel" name="no_telepon" class="form-control" value="{{ old('no_telepon') }}"
                            placeholder="081234567890" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap *</label>
                        <textarea name="alamat_lengkap" class="form-control" rows="3" placeholder="Jl. Raya Sukolilo No. 123" required>{{ old('alamat_lengkap') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Puskesmas *</label>
                        <select name="puskesmas_id" class="form-select" required>
                            <option value="">-- Pilih Puskesmas --</option>
                            @foreach ($puskesmas as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('puskesmas_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_puskesmas }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih puskesmas terdekat</small>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"
                    style="background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%); color: white;">
                    <i class="fas fa-lock me-2"></i>Data Akun
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            placeholder="nama@email.com" required>
                        <small class="text-muted">Gunakan email yang masih aktif</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password *</label>
                        <div class="input-group">
                            <input :type="showPassword ? 'text' : 'password'" name="password" class="form-control"
                                placeholder="Minimal 8 karakter" required>
                            <button type="button" class="btn btn-outline-secondary"
                                @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password *</label>
                        <input :type="showPassword ? 'text' : 'password'" name="password_confirmation"
                            class="form-control" placeholder="Ketik ulang password" required>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Perhatian:</strong>
                <ul class="mb-0 mt-2" style="font-size: 13px;">
                    <li>Akun akan menunggu persetujuan admin puskesmas</li>
                    <li>Beberapa fitur terbatas hingga akun disetujui</li>
                </ul>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
            </button>

            <div class="text-center">
                <p class="text-muted mb-0">Sudah punya akun? <a href="{{ route('app.login') }}"
                        class="text-primary fw-bold">Login di sini</a></p>
            </div>
        </form>
    </div>

    <div style="height: 60px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
