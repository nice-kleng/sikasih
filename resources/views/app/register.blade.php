<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#EC4899">
    <link rel="manifest" href="{{ url('manifest.json') }}">
    <title>Daftar - SIKASIH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gradient-to-br from-pink-50 via-purple-50 to-pink-100 min-h-screen">
    <div class="min-h-screen py-8 px-4">
        <div class="w-full max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-6">
                <a href="{{ route('app.login') }}"
                    class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Login
                </a>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Akun Baru</h1>
                <p class="text-gray-600">Lengkapi data diri Anda untuk mendaftar</p>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1 text-sm text-red-800">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('app.register.post') }}" class="space-y-5"
                    x-data="{ showPassword: false }">
                    @csrf

                    <!-- Data Pribadi Section -->
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Pribadi</h3>

                        <!-- Nama Lengkap -->
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap
                                *</label>
                            <input type="text" name="nama" id="nama" required value="{{ old('nama') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Contoh: Siti Nurhaliza">
                        </div>

                        <!-- NIK -->
                        <div class="mb-4">
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK (16 digit)
                                *</label>
                            <input type="text" name="nik" id="nik" required value="{{ old('nik') }}"
                                maxlength="16" pattern="[0-9]{16}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="3578125505950001">
                            <p class="text-xs text-gray-500 mt-1">Sesuai KTP</p>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="mb-4">
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                Lahir *</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" required
                                value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <!-- No. Telepon -->
                        <div class="mb-4">
                            <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No.
                                Telepon/WhatsApp *</label>
                            <input type="tel" name="no_telepon" id="no_telepon" required
                                value="{{ old('no_telepon') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="081234567890">
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Alamat
                                Lengkap *</label>
                            <textarea name="alamat_lengkap" id="alamat_lengkap" required rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Jl. Raya Sukolilo No. 123">{{ old('alamat_lengkap') }}</textarea>
                        </div>

                        <!-- Puskesmas -->
                        <div>
                            <label for="puskesmas_id" class="block text-sm font-medium text-gray-700 mb-2">Puskesmas
                                *</label>
                            <select name="puskesmas_id" id="puskesmas_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">-- Pilih Puskesmas --</option>
                                @foreach ($puskesmas as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('puskesmas_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_puskesmas }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih puskesmas terdekat dengan tempat tinggal Anda
                            </p>
                        </div>
                    </div>

                    <!-- Data Akun Section -->
                    <div class="pt-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Akun</h3>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" required
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="nama@email.com">
                            <p class="text-xs text-gray-500 mt-1">Gunakan email yang masih aktif</p>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password
                                *</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent pr-12"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password *</label>
                            <input :type="showPassword ? 'text' : 'password'" name="password_confirmation"
                                id="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Ketik ulang password">
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1 text-sm text-blue-800">
                                <p class="font-semibold mb-1">Perhatian</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Akun Anda akan menunggu persetujuan admin puskesmas</li>
                                    <li>Anda akan menerima notifikasi setelah akun disetujui</li>
                                    <li>Beberapa fitur terbatas hingga akun disetujui</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 rounded-lg transition-colors shadow-lg hover:shadow-xl">
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('app.login') }}"
                            class="text-primary-600 hover:text-primary-700 font-semibold">
                            Login di sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer Info -->
            <p class="text-center text-xs text-gray-600 mt-6">
                Dengan mendaftar, Anda menyetujui
                <a href="#" class="text-primary-600 hover:text-primary-700">Syarat & Ketentuan</a>
                serta
                <a href="#" class="text-primary-600 hover:text-primary-700">Kebijakan Privasi</a>
            </p>
        </div>
    </div>
</body>

</html>
