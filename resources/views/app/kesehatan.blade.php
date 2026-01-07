@extends('layouts.app')
@section('title', 'Kesehatan')
@section('page-title', 'Riwayat Kesehatan')
@section('header-icon', 'fa-heartbeat')

@push('styles')
    <style>
        /* Summary Cards, Tabs, Timeline styles from riwayat.html */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 15px 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .summary-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 20px;
        }

        .summary-card-icon.anc {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1976d2;
        }

        .summary-card-icon.skrining {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            color: #7b1fa2;
        }

        .summary-card-icon.lab {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            color: #f57c00;
        }

        .summary-card-value {
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        .summary-card-label {
            font-size: 11px;
            color: #666;
        }

        .filter-tabs {
            background: white;
            border-radius: 12px;
            padding: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 5px;
        }

        .filter-tab {
            flex: 1;
            padding: 10px;
            border: none;
            background: transparent;
            color: #666;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #ff6b9d 0%, #ffc0cb 100%);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-dot {
            position: absolute;
            left: -24px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ff6b9d;
            border: 3px solid #ff6b9d;
            box-shadow: 0 2px 8px rgba(255, 107, 157, 0.3);
            z-index: 2;
        }

        .timeline-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            cursor: pointer;
        }

        .timeline-card:hover {
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.2);
            transform: translateY(-2px);
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }

        .timeline-date {
            display: flex;
            flex-direction: column;
        }

        .timeline-day {
            font-size: 18px;
            font-weight: 700;
            color: #ff6b9d;
        }

        .timeline-month {
            font-size: 11px;
            color: #999;
        }

        .timeline-badge {
            background: #ff6b9d;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .timeline-badge.krr {
            background: #28a745;
        }

        .timeline-badge.krt {
            background: #ffc107;
            color: #333;
        }

        .timeline-badge.krst {
            background: #dc3545;
        }

        .timeline-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .timeline-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #666;
        }

        .info-item i {
            color: #ff6b9d;
            width: 14px;
        }

        .timeline-details {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #e0e0e0;
        }

        .timeline-details.hidden {
            display: none;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid #f5f5f5;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            text-align: right;
        }

        .detail-value.normal {
            color: #28a745;
        }

        .expand-btn {
            width: 100%;
            margin-top: 10px;
            padding: 8px;
            background: #f5f5f5;
            border: none;
            border-radius: 8px;
            color: #ff6b9d;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items-center;
            justify-content: center;
            gap: 8px;
        }

        .expand-btn:hover {
            background: #ffe8f2;
        }

        .expand-btn.expanded i {
            transform: rotate(180deg);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 700;
            color: #999;
            margin-bottom: 10px;
        }

        .empty-text {
            font-size: 14px;
            color: #999;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 py-3" x-data="{ activeTab: 'anc' }">

        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-icon anc"><i class="fas fa-clipboard-list"></i></div>
                <div class="summary-card-value">{{ $jumlahANC ?? 0 }}</div>
                <div class="summary-card-label">ANC</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon skrining"><i class="fas fa-check-circle"></i></div>
                <div class="summary-card-value">{{ $jumlahSkrining ?? 0 }}</div>
                <div class="summary-card-label">Skrining</div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon lab"><i class="fas fa-flask"></i></div>
                <div class="summary-card-value">{{ $jumlahLab ?? 0 }}</div>
                <div class="summary-card-label">Lab</div>
            </div>
        </div>

        <div class="filter-tabs">
            <button @click="activeTab = 'anc'" :class="{ 'active': activeTab === 'anc' }"
                class="filter-tab">ANC</button>
            <button @click="activeTab = 'skrining'" :class="{ 'active': activeTab === 'skrining' }"
                class="filter-tab">Skrining</button>
            <button @click="activeTab = 'lab'" :class="{ 'active': activeTab === 'lab' }"
                class="filter-tab">Lab</button>
        </div>

        <!-- ANC Tab -->
        <div x-show="activeTab === 'anc'" x-cloak>
            <div class="timeline">
                @forelse($pemeriksaan ?? [] as $p)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-card">
                            <div class="timeline-header">
                                <div class="timeline-date">
                                    <div class="timeline-day">{{ $p->tanggal_pemeriksaan->format('d M') }}</div>
                                    <div class="timeline-month">{{ $p->tanggal_pemeriksaan->format('Y') }}</div>
                                </div>
                                <div class="timeline-badge">ANC ke-{{ $p->kunjungan_ke }}</div>
                            </div>
                            <div class="timeline-title">Pemeriksaan Antenatal Care</div>
                            <div class="timeline-info">
                                @if ($p->tenagaKesehatan)
                                    <div class="info-item"><i
                                            class="fas fa-user-nurse"></i><span>{{ $p->tenagaKesehatan->user->nama }}</span>
                                    </div>
                                @endif
                                <div class="info-item"><i class="fas fa-clock"></i><span>UK: {{ $p->usia_kehamilan_minggu }}
                                        minggu</span></div>
                            </div>
                            <div class="timeline-details hidden" id="details-anc-{{ $p->id }}">
                                <div class="detail-row"><span class="detail-label">BB:</span><span
                                        class="detail-value">{{ $p->berat_badan }} kg</span></div>
                                <div class="detail-row"><span class="detail-label">TD:</span><span
                                        class="detail-value normal">{{ $p->tekanan_darah }}</span></div>
                                @if ($p->tinggi_fundus)
                                    <div class="detail-row"><span class="detail-label">TFU:</span><span
                                            class="detail-value">{{ $p->tinggi_fundus }} cm</span></div>
                                @endif
                                @if ($p->denyut_jantung_janin)
                                    <div class="detail-row"><span class="detail-label">DJJ:</span><span
                                            class="detail-value normal">{{ $p->denyut_jantung_janin }} x/mnt</span></div>
                                @endif
                            </div>
                            <button class="expand-btn" onclick="toggleDetails('anc-{{ $p->id }}')"><span>Lihat
                                    Detail</span><i class="fas fa-chevron-down"></i></button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-clipboard-list"></i></div>
                        <div class="empty-title">Belum Ada Riwayat ANC</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Skrining Tab -->
        <div x-show="activeTab === 'skrining'" x-cloak>
            <a href="{{ route('app.skrining.create') }}" class="card border-0 shadow-sm mb-3 text-decoration-none"
                style="background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fas fa-plus-circle me-2"></i>Skrining Mandiri</h5>
                            <p class="mb-0" style="font-size: 13px;">Cek risiko kehamilan Anda</p>
                        </div><i class="fas fa-arrow-right fa-2x"></i>
                    </div>
                </div>
            </a>

            <div class="timeline">
                @forelse($skrining ?? [] as $s)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-card" data-bs-toggle="modal"
                            data-bs-target="#skriningModal{{ $s->id }}">
                            <div class="timeline-header">
                                <div class="timeline-date">
                                    <div class="timeline-day">{{ $s->tanggal_skrining->format('d M') }}</div>
                                    <div class="timeline-month">{{ $s->tanggal_skrining->format('Y') }}</div>
                                </div>
                                <div class="timeline-badge {{ strtolower($s->kategori_risiko) }}">
                                    {{ $s->kategori_risiko }}</div>
                            </div>
                            <div class="timeline-title">Skrining {{ ucfirst($s->jenis_skrining) }}</div>
                            <div class="timeline-info">
                                <div class="info-item"><i class="fas fa-calculator"></i><span>Skor:
                                        {{ $s->total_skor }}</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Skrining -->
                    <div class="modal fade" id="skriningModal{{ $s->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 20px;">
                                <div class="modal-body p-4 text-center">
                                    <div class="mb-3 mx-auto rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 80px; height: 80px; background: {{ $s->kategori_risiko === 'KRR' ? '#d1f4e0' : ($s->kategori_risiko === 'KRT' ? '#fff3cd' : '#f8d7da') }};">
                                        <i class="fas {{ $s->kategori_risiko === 'KRR' ? 'fa-check-circle' : ($s->kategori_risiko === 'KRT' ? 'fa-exclamation-circle' : 'fa-times-circle') }} fa-3x"
                                            style="color: {{ $s->kategori_risiko === 'KRR' ? '#28a745' : ($s->kategori_risiko === 'KRT' ? '#ffc107' : '#dc3545') }};"></i>
                                    </div>
                                    <h4 class="fw-bold">Hasil Skrining</h4>
                                    <div class="display-4 fw-bold text-primary">{{ $s->total_skor }}</div>
                                    <h5
                                        class="badge {{ $s->kategori_risiko === 'KRR' ? 'bg-success' : ($s->kategori_risiko === 'KRT' ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2 mb-3">
                                        {{ $s->kategori_risiko === 'KRR' ? 'Risiko Rendah (KRR)' : ($s->kategori_risiko === 'KRT' ? 'Risiko Tinggi (KRT)' : 'Risiko Sangat Tinggi (KRST)') }}
                                    </h5>
                                    @if ($s->rekomendasi)
                                        <div class="card border-0 bg-light text-start mb-3">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-2"><i
                                                        class="fas fa-clipboard-list text-primary me-2"></i>Rekomendasi
                                                </h6>
                                                <ul class="mb-0" style="font-size: 13px;">
                                                    @foreach ($s->rekomendasi->rekomendasi_list as $rec)
                                                        <li>{{ $rec }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    <small class="text-muted">{{ $s->tanggal_skrining->format('d F Y') }}</small>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="empty-title">Belum Ada Skrining</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Lab Tab -->
        <div x-show="activeTab === 'lab'" x-cloak>
            <div class="timeline">
                @forelse($laboratorium ?? [] as $l)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-card">
                            <div class="timeline-header">
                                <div class="timeline-date">
                                    <div class="timeline-day">{{ $l->tanggal_pemeriksaan->format('d M') }}</div>
                                    <div class="timeline-month">{{ $l->tanggal_pemeriksaan->format('Y') }}</div>
                                </div>
                                <div class="timeline-badge" style="background: #f57c00;">Lab</div>
                            </div>
                            <div class="timeline-title">{{ $l->jenis_pemeriksaan }}</div>
                            <div class="timeline-details hidden" id="details-lab-{{ $l->id }}">
                                @if ($l->hasil_lab)
                                    @foreach (json_decode($l->hasil_lab, true) as $k => $v)
                                        <div class="detail-row"><span
                                                class="detail-label">{{ ucfirst(str_replace('_', ' ', $k)) }}:</span><span
                                                class="detail-value">{{ $v }}</span></div>
                                    @endforeach
                                @endif
                            </div>
                            <button class="expand-btn" onclick="toggleDetails('lab-{{ $l->id }}')"><span>Lihat
                                    Detail</span><i class="fas fa-chevron-down"></i></button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-flask"></i></div>
                        <div class="empty-title">Belum Ada Lab</div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function toggleDetails(id) {
                const details = document.getElementById(`details-${id}`);
                if (!details) return;
                const btn = details.nextElementSibling;
                const icon = btn.querySelector('i');
                const text = btn.querySelector('span');
                if (details.classList.contains('hidden')) {
                    details.classList.remove('hidden');
                    btn.classList.add('expanded');
                    text.textContent = 'Tutup Detail';
                } else {
                    details.classList.add('hidden');
                    btn.classList.remove('expanded');
                    text.textContent = 'Lihat Detail';
                }
            }
        </script>
    @endpush
@endsection
