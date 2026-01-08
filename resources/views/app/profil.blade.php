@extends('layouts.app')
@section('title', 'Profil')
@section('page-title', 'Profil Saya')
@section('header-icon', 'fa-user')
@section('content')
    <div class="container-fluid px-3 py-3" x-data="{ editMode: false, editPassword: false }">

        <!-- Profile Header Card -->
        <div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);">
            <div class="card-body text-white text-center py-4">
                <form method="POST" action="{{ route('app.profil.foto') }}" enctype="multipart/form-data" id="photoForm"
                    style="display: none;">
                    @csrf
                    @method('PUT')
                    <input type="file" name="foto" accept="image/*" id="foto-input" onchange="this.form.submit()">
                </form>

                <div class="position-relative d-inline-block mb-3" onclick="document.getElementById('foto-input').click();"
                    style="cursor: pointer;">
                    @if ($user->foto)
                        <img src="{{ Storage::url($user->foto) }}" alt="{{ $user->nama }}"
                            class="rounded-circle border border-3 border-white"
                            style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="rounded-circle border border-3 border-white bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                    @endif
                    <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2"
                        style="box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                        <i class="fas fa-camera text-primary"></i>
                    </div>
                </div>

                <h4 class="fw-bold mb-1">{{ $user->nama }}</h4>
                <p class="mb-2 opacity-75">{{ $user->email }}</p>

                @if ($user->status === 'pending')
                    <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                @else
                    <span class="badge bg-success">Akun Aktif</span>
                @endif
            </div>
        </div>

        <!-- Data Pribadi -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-user me-2"></i>Data Pribadi</strong>
                <button @click="editMode = !editMode" class="btn btn-sm btn-light" type="button">
                    <span x-text="editMode ? 'Batal' : 'Edit'"></span>
                </button>
            </div>
            <div class="card-body">
                <div x-show="!editMode">
                    <div class="mb-2">
                        <small class="text-muted d-block">Nama Lengkap</small>
                        <strong>{{ $user->nama }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">No. Telepon</small>
                        <strong>{{ $user->no_telepon }}</strong>
                    </div>
                    @if ($ibuHamil)
                        <div class="mb-2">
                            <small class="text-muted d-block">Alamat</small>
                            <strong>{{ $ibuHamil->alamat_lengkap }}</strong>
                        </div>
                        @if ($ibuHamil->rt || $ibuHamil->rw)
                            <div class="mb-2">
                                <small class="text-muted d-block">RT/RW</small>
                                <strong>{{ $ibuHamil->rt ?? '-' }} / {{ $ibuHamil->rw ?? '-' }}</strong>
                            </div>
                        @endif
                    @endif
                </div>

                <form method="POST" action="{{ route('app.profil.update') }}" x-show="editMode" x-cloak>
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">No. Telepon</label>
                        <input type="tel" name="no_telepon" class="form-control"
                            value="{{ old('no_telepon', $user->no_telepon) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" class="form-control" rows="2">{{ old('alamat_lengkap', $ibuHamil->alamat_lengkap ?? '') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label fw-bold">RT</label>
                            <input type="text" name="rt" class="form-control"
                                value="{{ old('rt', $ibuHamil->rt ?? '') }}" maxlength="3">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">RW</label>
                            <input type="text" name="rw" class="form-control"
                                value="{{ old('rw', $ibuHamil->rw ?? '') }}" maxlength="3">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Data Kehamilan -->
        @if ($ibuHamil)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <strong><i class="fas fa-baby me-2"></i>Data Kehamilan</strong>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">No. RM</small>
                            <strong>{{ $ibuHamil->no_rm }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">NIK</small>
                            <strong>{{ $ibuHamil->nik }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Tanggal Lahir</small>
                            <strong>{{ $ibuHamil->tanggal_lahir?->format('d M Y') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Umur</small>
                            <strong>{{ $ibuHamil->umur }} tahun</strong>
                        </div>
                        @if ($ibuHamil->hpht)
                            <div class="col-6">
                                <small class="text-muted d-block">HPHT</small>
                                <strong>{{ $ibuHamil->hpht?->format('d M Y') }}</strong>
                            </div>
                        @endif
                        @if ($ibuHamil->hpl)
                            <div class="col-6">
                                <small class="text-muted d-block">HPL</small>
                                <strong>{{ $ibuHamil->hpl?->format('d M Y') }}</strong>
                            </div>
                        @endif
                        @if ($ibuHamil->puskesmas)
                            <div class="col-12">
                                <small class="text-muted d-block">Puskesmas</small>
                                <strong>{{ $ibuHamil->puskesmas->nama_puskesmas }}</strong>
                            </div>
                        @endif
                    </div>
                    <small class="text-muted d-block mt-3 text-center"><i class="fas fa-info-circle me-1"></i>Data
                        kehamilan dikelola oleh tenaga kesehatan</small>
                </div>
            </div>
        @endif

        <!-- Ubah Password -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-lock me-2"></i>Keamanan</strong>
                <button @click="editPassword = !editPassword" class="btn btn-sm btn-light" type="button">
                    <span x-text="editPassword ? 'Batal' : 'Ubah Password'"></span>
                </button>
            </div>
            <div class="card-body">
                <div x-show="!editPassword">
                    <small class="text-muted">Password terakhir diubah: {{ $user->updated_at->diffForHumans() }}</small>
                </div>

                <form method="POST" action="{{ route('app.profil.password') }}" x-show="editPassword" x-cloak>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required
                            minlength="8">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Menu -->
        <a href="{{ route('app.pengaturan') }}" class="card border-0 shadow-sm mb-2 text-decoration-none">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-cog fa-lg text-primary me-3"></i>
                    <strong class="text-dark">Pengaturan</strong>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        </a>

        <form method="POST" action="{{ route('app.logout') }}">
            @csrf
            <button type="submit" class="card border-0 shadow-sm mb-3 w-100 text-start">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sign-out-alt fa-lg text-danger me-3"></i>
                        <strong class="text-danger">Keluar</strong>
                    </div>
                </div>
            </button>
        </form>

    </div>
@endsection
