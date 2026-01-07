@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('header-icon', 'fa-cog')
@section('content')
    <div class="container-fluid px-3 py-3">

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <strong><i class="fas fa-bell me-2"></i>Notifikasi</strong>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Pemeriksaan</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Skrining</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Artikel Baru</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <strong><i class="fas fa-info-circle me-2"></i>Tentang</strong>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>SIKASIH v1.0.0</strong></p>
                <p class="text-muted mb-0" style="font-size: 13px;">Sistem Informasi Kesehatan Ibu Hamil</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <a href="#" class="text-primary text-decoration-none d-block mb-2">
                    <i class="fas fa-file-alt me-2"></i>Syarat & Ketentuan
                </a>
                <a href="#" class="text-primary text-decoration-none d-block">
                    <i class="fas fa-shield-alt me-2"></i>Kebijakan Privasi
                </a>
            </div>
        </div>

    </div>
@endsection
