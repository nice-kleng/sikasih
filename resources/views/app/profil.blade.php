@extends('layouts.app')
@section('title', 'Profil')
@section('page-title', 'Profil Saya')
@section('content')
    <div class="p-4 space-y-6" x-data="{ editMode: false, editPassword: false }">
        <!-- Profile Header -->
        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-6 text-white text-center">
            <form method="POST" action="{{ route('app.profil.update') }}" enctype="multipart/form-data" x-show="editMode"
                class="mb-4">
                @csrf
                @method('PUT')
                <input type="file" name="foto" accept="image/*" class="hidden" id="foto-input"
                    onchange="previewImage(event)">
            </form>
            <div class="w-24 h-24 mx-auto mb-4 rounded-full border-4 border-white overflow-hidden relative group cursor-pointer"
                onclick="document.getElementById('foto-input').click()">
                @if ($user->foto)
                    <img src="{{ Storage::url($user->foto) }}" alt="{{ $user->nama }}" class="w-full h-full object-cover"
                        id="foto-preview">
                @else
                    <div class="w-full h-full bg-white/20 flex items-center justify-center" id="foto-preview">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>
                @endif
                <div
                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold mb-1">{{ $user->nama }}</h2>
            <p class="text-primary-100 text-sm">{{ $user->email }}</p>
            @if ($user->status === 'pending')
                <span
                    class="inline-block mt-3 px-3 py-1 bg-yellow-400 text-yellow-900 text-xs font-semibold rounded-full">Menunggu
                    Persetujuan</span>
            @else
                <span
                    class="inline-block mt-3 px-3 py-1 bg-green-400 text-green-900 text-xs font-semibold rounded-full">Akun
                    Aktif</span>
            @endif
        </div>

        <!-- Data Pribadi -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900 dark:text-white">Data Pribadi</h3>
                <button @click="editMode = !editMode"
                    class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 font-medium">
                    <span x-text="editMode ? 'Batal' : 'Edit'"></span>
                </button>
            </div>

            <div x-show="!editMode" class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Nama Lengkap</span><span
                        class="font-medium text-gray-900 dark:text-white">{{ $user->nama }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Email</span><span
                        class="font-medium text-gray-900 dark:text-white">{{ $user->email }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">No. Telepon</span><span
                        class="font-medium text-gray-900 dark:text-white">{{ $user->no_telepon }}</span></div>
                @if ($ibuHamil)
                    <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Alamat</span><span
                            class="font-medium text-gray-900 dark:text-white text-right">{{ $ibuHamil->alamat_lengkap }}</span>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('app.profil.update') }}" x-show="editMode" x-cloak class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon</label>
                    <input type="tel" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('alamat_lengkap', $ibuHamil->alamat_lengkap ?? '') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">RT</label>
                        <input type="text" name="rt" value="{{ old('rt', $ibuHamil->rt ?? '') }}" maxlength="3"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">RW</label>
                        <input type="text" name="rw" value="{{ old('rw', $ibuHamil->rw ?? '') }}" maxlength="3"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Data Kehamilan (Read Only) -->
        @if ($ibuHamil)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Data Kehamilan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">No. RM</span><span
                            class="font-medium text-gray-900 dark:text-white">{{ $ibuHamil->no_rm }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">NIK</span><span
                            class="font-medium text-gray-900 dark:text-white">{{ $ibuHamil->nik }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Tanggal
                            Lahir</span><span
                            class="font-medium text-gray-900 dark:text-white">{{ $ibuHamil->tanggal_lahir?->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Umur</span><span
                            class="font-medium text-gray-900 dark:text-white">{{ $ibuHamil->umur }} tahun</span></div>
                    @if ($ibuHamil->hpht)
                        <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">HPHT</span><span
                                class="font-medium text-gray-900 dark:text-white">{{ $ibuHamil->hpht?->format('d M Y') }}</span>
                        </div>
                    @endif
                    @if ($ibuHamil->hpl)
                        <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">HPL</span><span
                                class="font-medium text-gray-900 dark:text-white">{{ $ibuHamil->hpl?->format('d M Y') }}</span>
                        </div>
                    @endif
                    @if ($ibuHamil->usia_kehamilan_minggu)
                        <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Usia
                                Kehamilan</span><span
                                class="font-medium text-gray-900 dark:text-white">{{ $ibuHamil->usia_kehamilan_minggu }}
                                minggu</span></div>
                    @endif
                    @if ($ibuHamil->trimester)
                        <div class="flex justify-between"><span
                                class="text-gray-500 dark:text-gray-400">Trimester</span><span
                                class="font-medium text-gray-900 dark:text-white">{{ $ibuHamil->trimester }}</span></div>
                    @endif
                    @if ($ibuHamil->puskesmas)
                        <div class="flex justify-between"><span
                                class="text-gray-500 dark:text-gray-400">Puskesmas</span><span
                                class="font-medium text-gray-900 dark:text-white text-right">{{ $ibuHamil->puskesmas->nama_puskesmas }}</span>
                        </div>
                    @endif
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center">Data kehamilan hanya dapat diubah oleh
                    tenaga kesehatan</p>
            </div>
        @endif

        <!-- Ubah Password -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900 dark:text-white">Keamanan</h3>
                <button @click="editPassword = !editPassword"
                    class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 font-medium">
                    <span x-text="editPassword ? 'Batal' : 'Ubah Password'"></span>
                </button>
            </div>

            <div x-show="!editPassword" class="text-sm text-gray-500 dark:text-gray-400">
                Password terakhir diubah: <span class="font-medium">{{ $user->updated_at->diffForHumans() }}</span>
            </div>

            <form method="POST" action="{{ route('app.profil.password') }}" x-show="editPassword" x-cloak
                class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Saat
                        Ini</label>
                    <input type="password" name="current_password" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                    <input type="password" name="password" required minlength="8"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password
                        Baru</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <button type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 rounded-lg transition-colors">
                    Ubah Password
                </button>
            </form>
        </div>

        <!-- Menu Lainnya -->
        <div class="space-y-3">
            <a href="{{ route('app.pengaturan') }}"
                class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center"><svg
                            class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg></div>
                    <span class="font-medium text-gray-900 dark:text-white">Pengaturan</span>
                </div>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <form method="POST" action="{{ route('app.logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl p-4 shadow hover:shadow-md transition-shadow text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="font-medium text-red-600 dark:text-red-400">Keluar</span>
                    </div>
                </button>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('foto-preview').innerHTML =
                            `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    }
                    reader.readAsDataURL(file);
                    // Auto submit form
                    event.target.closest('form').submit();
                }
            }
        </script>
    @endpush
@endsection
