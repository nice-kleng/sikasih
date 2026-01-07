<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#ff6b9d">
    <link rel="manifest" href="{{ url('manifest.json') }}">
    <title>Login - SIKASIH</title>
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
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(255, 107, 157, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 157, 0.4);
        }

        .form-control {
            border: 1px solid #ffcce0;
            border-radius: 10px;
            padding: 12px 15px;
        }

        .form-control:focus {
            border-color: #ff6b9d;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.25);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="w-100">
            <div class="login-card">
                <div class="login-header">
                    <div class="logo-circle">
                        <i class="fas fa-heart fa-2x" style="color: #ff6b9d;"></i>
                    </div>
                    <h2 class="mb-2">SIKASIH</h2>
                    <p class="mb-0 opacity-75" style="font-size: 13px;">Sistem Informasi Kesehatan Ibu Hamil</p>
                </div>

                <div class="p-4">
                    <h4 class="text-center mb-4 fw-bold">Masuk ke Akun Anda</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex align-items-start">
                            <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('app.login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                placeholder="nama@email.com" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="form-check mb-4">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </button>
                    </form>

                    <div class="text-center">
                        <hr class="my-3">
                        <p class="text-muted mb-2">Belum punya akun?</p>
                        <a href="{{ route('app.register') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </a>
                    </div>

                    <p class="text-center text-muted mt-4 mb-0" style="font-size: 12px;">
                        Untuk tenaga kesehatan & admin, login melalui
                        <a href="/admin/login" class="text-primary">portal admin</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
