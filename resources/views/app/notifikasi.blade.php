@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('header-icon', 'fa-bell')
@section('content')
    <div class="container-fluid px-3 py-3">

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body d-flex gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px;">
                        <i class="fas fa-info-circle fa-lg text-primary"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">Selamat datang di SIKASIH!</h6>
                    <p class="mb-1 text-muted" style="font-size: 13px;">Terima kasih telah mendaftar. Lengkapi profil Anda
                        untuk pengalaman yang lebih baik.</p>
                    <small class="text-muted"><i class="far fa-clock me-1"></i>Hari ini</small>
                </div>
            </div>
        </div>

        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-4x text-muted mb-3 opacity-50"></i>
            <h6 class="text-muted">Belum ada notifikasi lain</h6>
            <p class="text-muted" style="font-size: 13px;">Notifikasi akan muncul di sini</p>
        </div>

    </div>
@endsection
