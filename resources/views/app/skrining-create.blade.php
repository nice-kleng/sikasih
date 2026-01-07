@extends('layouts.app')
@section('title', 'Skrining Mandiri')
@section('page-title', 'Deteksi Risiko Kehamilan')
@section('header-icon', 'fa-check-circle')

@push('styles')
    <style>
        /* Styles from hamil.html */
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .info-card h3 {
            color: #ff6b9d;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title {
            color: #333;
            font-size: 15px;
            font-weight: 700;
            margin: 25px 0 15px 0;
            padding-left: 10px;
            border-left: 4px solid #ff6b9d;
        }

        .risk-group {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .group-header {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .checkbox-item:hover {
            background: #fff5f9;
            border-color: #ffcce0;
        }

        .checkbox-item.checked {
            background: #ffe8f2;
            border-color: #ff6b9d;
        }

        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #ff6b9d;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .checkbox-label {
            cursor: pointer;
            flex: 1;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }

        .score-badge {
            background: #ff6b9d;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .result-section {
            position: fixed;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            max-width: 480px;
            width: 100%;
            background: white;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            /* z-index: 99; */
            border-radius: 20px 20px 0 0;
        }

        .total-score {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .total-score .score {
            font-size: 28px;
            font-weight: 700;
            color: #ff6b9d;
        }

        .check-btn {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items-center;
            justify-content: center;
            gap: 10px;
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            border-radius: 20px;
            padding: 30px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .modal-icon.low-risk {
            background: linear-gradient(135deg, #d1f4e0 0%, #a8e6cf 100%);
            color: #28a745;
        }

        .modal-icon.medium-risk {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe5a1 100%);
            color: #ffc107;
        }

        .modal-icon.high-risk {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #dc3545;
        }

        .modal-score {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .modal-category {
            font-size: 20px;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .modal-category.low-risk {
            background: #28a745;
            color: white;
        }

        .modal-category.medium-risk {
            background: #ffc107;
            color: #333;
        }

        .modal-category.high-risk {
            background: #dc3545;
            color: white;
        }

        .recommendation {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: left;
        }

        .recommendation h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }

        .recommendation ul {
            margin: 0;
            padding-left: 20px;
        }

        .recommendation li {
            margin-bottom: 10px;
            line-height: 1.6;
            color: #555;
        }

        .close-modal-btn {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .reset-btn {
            background: #e9ecef;
            color: #666;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <form method="POST" action="{{ route('app.skrining.store') }}" id="skriningForm">
        @csrf
        <div class="container-fluid px-3 py-3" style="padding-bottom: 180px;">

            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> Informasi Penting</h3>
                <p style="color: #666; font-size: 13px; line-height: 1.6; margin: 0;">Centang kondisi yang sesuai dengan
                    keadaan Anda saat ini. Hasil skrining akan membantu menentukan tingkat risiko kehamilan dan rekomendasi
                    tempat persalinan yang tepat.</p>
            </div>

            <input type="hidden" name="total_skor" id="totalSkorInput" value="2">
            <input type="hidden" name="kategori_risiko" id="kategoriInput">
            <input type="hidden" name="rekomendasi_tempat_bersalin" id="rekomendasiInput">

            <div class="section-title">Kelompok Faktor Risiko I</div>
            <div class="risk-group">
                <div class="group-header"><i class="fas fa-user"></i> Faktor Demografi & Reproduksi</div>

                <div class="checkbox-item">
                    <input type="checkbox" name="faktor_risiko[]" value="terlalu_muda" data-score="4"
                        onchange="handleCheckboxChange(this)">
                    <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Terlalu muda, hamil pertama ≤ 16 tahun
                    </div>
                    <span class="score-badge">4</span>
                </div>

                <div class="checkbox-item">
                    <input type="checkbox" name="faktor_risiko[]" value="terlalu_tua" data-score="4"
                        onchange="handleCheckboxChange(this)">
                    <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Terlalu tua, hamil pertama ≥ 35 tahun
                    </div>
                    <span class="score-badge">4</span>
                </div>

                <div class="checkbox-item">
                    <input type="checkbox" name="faktor_risiko[]" value="terlalu_lambat" data-score="4"
                        onchange="handleCheckboxChange(this)">
                    <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Terlalu lambat hamil I, kawin ≥ 4 tahun
                    </div>
                    <span class="score-badge">4</span>
                </div>

                <div class="checkbox-item">
                    <input type="checkbox" name="faktor_risiko[]" value="terlalu_lama" data-score="4"
                        onchange="handleCheckboxChange(this)">
                    <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Terlalu lama hamil lagi (≥ 10 tahun)
                    </div>
                    <span class="score-badge">4</span>
                </div>

                <div class="checkbox-item">
                    <input type="checkbox" name="faktor_risiko[]" value="terlalu_cepat" data-score="4"
                        onchange="handleCheckboxChange(this)">
                    <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Terlalu cepat hamil lagi (< 2
                            tahun)</div>
                            <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="terlalu_banyak" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Terlalu banyak anak, ≥ 4</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="terlalu_tua_anak4" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Terlalu tua, umur ≥ 35 tahun, punya
                            anak ≥ 4</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="tinggi_badan" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Tinggi badan ≤ 145 cm</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="pernah_gagal" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Pernah gagal kehamilan</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="pernah_melahirkan_cacat" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Pernah melahirkan dengan cacat
                            bawaan</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="pernah_melahirkan_mati" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Pernah melahirkan bayi mati</div>
                        <span class="score-badge">4</span>
                    </div>
                </div>

                <div class="section-title">Kelompok Faktor Risiko II</div>
                <div class="risk-group">
                    <div class="group-header"><i class="fas fa-heartbeat"></i> Riwayat Obstetri Buruk</div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="pernah_sc" data-score="8"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Pernah bedah caesar</div>
                        <span class="score-badge">8</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="bb_rendah" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Pernah melahirkan bayi dengan BB
                            < 2500 gram atau> 4000 gram
                        </div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="hamil_kembar" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Hamil kembar 2 atau lebih</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="hidramnion" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Hidramnion/oligohidramnion</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="bayi_mati" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Bayi mati dalam kandungan</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="kehamilan_lebih_bulan" data-score="4"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Kehamilan lebih bulan</div>
                        <span class="score-badge">4</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="letak_sungsang" data-score="8"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Letak sungsang</div>
                        <span class="score-badge">8</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="letak_lintang" data-score="8"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Letak lintang</div>
                        <span class="score-badge">8</span>
                    </div>
                </div>

                <div class="section-title">Kelompok Faktor Risiko III</div>
                <div class="risk-group">
                    <div class="group-header"><i class="fas fa-exclamation-triangle"></i> Komplikasi Serius</div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="perdarahan" data-score="8"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Perdarahan dalam kehamilan ini
                        </div>
                        <span class="score-badge">8</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="preeklampsia" data-score="8"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Preeklampsia (tekanan darah
                            tinggi dalam kehamilan)</div>
                        <span class="score-badge">8</span>
                    </div>

                    <div class="checkbox-item">
                        <input type="checkbox" name="faktor_risiko[]" value="eklampsia" data-score="8"
                            onchange="handleCheckboxChange(this)">
                        <div class="checkbox-label" onclick="toggleCheckFromLabel(this)">Preeklampsia berat/kejang-kejang
                            (eklampsia)</div>
                        <span class="score-badge">8</span>
                    </div>
                </div>
            </div>

            <div class="result-section">
                <div class="total-score">
                    <span style="font-size: 14px; color: #666; font-weight: 600;">Total Skor:</span>
                    <span class="score" id="totalScore">2</span>
                </div>
                <button type="button" class="check-btn" onclick="showResult()">
                    <i class="fas fa-clipboard-check"></i> Lihat Hasil & Rekomendasi
                </button>
            </div>
    </form>

    <!-- Modal Hasil -->
    <div class="modal fade" id="resultModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="modal-icon" id="modalIcon"><i class="fas fa-heart fa-3x"></i></div>
                    <h2 class="modal-title fw-bold">Hasil Skrining</h2>
                    <div class="modal-score" id="modalScore">2</div>
                    <div class="modal-category" id="modalCategory">Risiko Rendah</div>

                    <div class="recommendation">
                        <h4><i class="fas fa-clipboard-list"></i> Rekomendasi</h4>
                        <ul id="recommendationList"></ul>
                    </div>

                    <button type="button" class="close-modal-btn" onclick="submitForm()">Simpan Hasil</button>
                    <button type="button" class="reset-btn" onclick="resetForm()"><i class="fas fa-redo"></i> Mulai
                        Ulang</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let totalScore = 2;

            function handleCheckboxChange(checkbox) {
                const item = checkbox.closest('.checkbox-item');
                if (checkbox.checked) {
                    item.classList.add('checked');
                } else {
                    item.classList.remove('checked');
                }
                calculateTotal();
            }

            function toggleCheckFromLabel(labelElement) {
                const item = labelElement.closest('.checkbox-item');
                const checkbox = item.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                handleCheckboxChange(checkbox);
            }

            function calculateTotal() {
                totalScore = 2;
                document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                    totalScore += parseInt(checkbox.dataset.score);
                });
                document.getElementById('totalScore').textContent = totalScore;
            }

            function showResult() {
                const modal = new bootstrap.Modal(document.getElementById('resultModal'));
                const modalIcon = document.getElementById('modalIcon');
                const modalScore = document.getElementById('modalScore');
                const modalCategory = document.getElementById('modalCategory');
                const recommendationList = document.getElementById('recommendationList');

                modalScore.textContent = totalScore;

                let category, recommendations, kategori, rekomendasi;

                if (totalScore <= 2) {
                    category = 'Risiko Rendah (KRR)';
                    kategori = 'KRR';
                    rekomendasi = 'Puskesmas atau Polindes';
                    modalIcon.className = 'modal-icon low-risk';
                    modalIcon.innerHTML = '<i class="fas fa-check-circle fa-3x"></i>';
                    modalCategory.className = 'modal-category low-risk';
                    modalCategory.textContent = category;
                    recommendations = [
                        'Ibu dapat melahirkan di Puskesmas atau Polindes',
                        'Lakukan pemeriksaan kehamilan rutin minimal 4 kali (1-1-2)',
                        'Konsumsi tablet tambah darah dan vitamin sesuai anjuran',
                        'Jaga pola makan bergizi seimbang',
                        'Istirahat yang cukup dan hindari stress',
                        'Persiapkan persalinan dengan baik'
                    ];
                } else if (totalScore >= 3 && totalScore <= 6) {
                    category = 'Risiko Tinggi (KRT)';
                    kategori = 'KRT';
                    rekomendasi = 'Puskesmas PONED atau Rumah Sakit';
                    modalIcon.className = 'modal-icon medium-risk';
                    modalIcon.innerHTML = '<i class="fas fa-exclamation-circle fa-3x"></i>';
                    modalCategory.className = 'modal-category medium-risk';
                    modalCategory.textContent = category;
                    recommendations = [
                        'Ibu perlu melahirkan di Puskesmas PONED atau Rumah Sakit',
                        'Diperlukan pemeriksaan lebih intensif oleh tenaga kesehatan',
                        'Konsultasi dengan dokter spesialis kandungan',
                        'Perhatikan tanda-tanda bahaya kehamilan',
                        'Siapkan donor darah dan transportasi darurat',
                        'Pertimbangkan untuk tinggal dekat fasilitas kesehatan menjelang persalinan'
                    ];
                } else {
                    category = 'Risiko Sangat Tinggi (KRST)';
                    kategori = 'KRST';
                    rekomendasi = 'Rumah Sakit';
                    modalIcon.className = 'modal-icon high-risk';
                    modalIcon.innerHTML = '<i class="fas fa-times-circle fa-3x"></i>';
                    modalCategory.className = 'modal-category high-risk';
                    modalCategory.textContent = category;
                    recommendations = [
                        'IBU HARUS melahirkan di Rumah Sakit',
                        'Segera konsultasi dengan dokter spesialis kandungan',
                        'Pemeriksaan dan monitoring ketat sangat diperlukan',
                        'Persiapkan biaya, donor darah, dan transportasi darurat',
                        'Jika terjadi tanda bahaya segera ke Rumah Sakit',
                        'Pertimbangkan rawat inap jika diperlukan',
                        'Keluarga harus siap mendampingi kapan saja'
                    ];
                }

                // Update hidden inputs
                document.getElementById('totalSkorInput').value = totalScore;
                document.getElementById('kategoriInput').value = kategori;
                document.getElementById('rekomendasiInput').value = rekomendasi;

                recommendationList.innerHTML = '';
                recommendations.forEach(rec => {
                    const li = document.createElement('li');
                    li.textContent = rec;
                    recommendationList.appendChild(li);
                });

                modal.show();
            }

            function submitForm() {
                document.getElementById('skriningForm').submit();
            }

            function resetForm() {
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                document.querySelectorAll('.checkbox-item').forEach(item => {
                    item.classList.remove('checked');
                });
                totalScore = 2;
                document.getElementById('totalScore').textContent = totalScore;
                bootstrap.Modal.getInstance(document.getElementById('resultModal')).hide();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            calculateTotal();
        </script>
    @endpush
@endsection
