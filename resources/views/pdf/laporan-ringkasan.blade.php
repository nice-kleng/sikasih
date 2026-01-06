<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ringkasan - {{ $puskesmas->nama_puskesmas }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #EC4899;
        }

        .header h1 {
            color: #EC4899;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        .header .periode {
            font-size: 11px;
            color: #888;
        }

        .info-puskesmas {
            background: #F9FAFB;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #EC4899;
        }

        .info-puskesmas table {
            width: 100%;
        }

        .info-puskesmas td {
            padding: 3px 0;
        }

        .info-puskesmas td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background: #EC4899;
            color: white;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table.data-table th {
            background: #F3F4F6;
            font-weight: bold;
            color: #374151;
        }

        table.data-table tr:nth-child(even) {
            background: #F9FAFB;
        }

        table.data-table td:nth-child(2),
        table.data-table td:nth-child(3),
        table.data-table td:nth-child(4) {
            text-align: center;
        }

        .highlight-box {
            display: inline-block;
            padding: 15px 20px;
            margin: 10px;
            border-radius: 8px;
            text-align: center;
            min-width: 150px;
        }

        .highlight-box.success {
            background: #D1FAE5;
            border: 2px solid #10B981;
        }

        .highlight-box.warning {
            background: #FEF3C7;
            border: 2px solid #F59E0B;
        }

        .highlight-box.danger {
            background: #FEE2E2;
            border: 2px solid #EF4444;
        }

        .highlight-box .label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .highlight-box .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .highlight-box .subtext {
            font-size: 10px;
            color: #888;
            margin-top: 5px;
        }

        .grid-2 {
            display: table;
            width: 100%;
        }

        .grid-item {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 5px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #888;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge.success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge.warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge.danger {
            background: #FEE2E2;
            color: #991B1B;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN RINGKASAN PUSKESMAS</h1>
        <h2>{{ $puskesmas->nama_puskesmas }}</h2>
        <p class="periode">Periode: {{ \Carbon\Carbon::parse($periode['dari'])->format('d F Y') }} -
            {{ \Carbon\Carbon::parse($periode['sampai'])->format('d F Y') }}
        </p>
    </div>

    <div class="info-puskesmas">
        <table>
            <tr>
                <td>Kode Puskesmas</td>
                <td>: {{ $puskesmas->kode_puskesmas }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: {{ $puskesmas->alamat }}</td>
            </tr>
            <tr>
                <td>Kecamatan / Kabupaten</td>
                <td>: {{ $puskesmas->kecamatan }} / {{ $puskesmas->kabupaten }}</td>
            </tr>
            <tr>
                <td>Kepala Puskesmas</td>
                <td>: {{ $puskesmas->kepala_puskesmas }}</td>
            </tr>
            <tr>
                <td>Tipe Puskesmas</td>
                <td>: {{ strtoupper($puskesmas->tipe) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">RINGKASAN DATA UTAMA</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Indikator</th>
                    <th>Jumlah</th>
                    <th>Target</th>
                    <th>Capaian (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Ibu Hamil Aktif</td>
                    <td>{{ $statistik['total_ibu_hamil'] ?? 0 }}</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Total Pemeriksaan ANC</td>
                    <td>{{ $statistik['total_pemeriksaan'] ?? 0 }}</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><strong>Cakupan K1</strong></td>
                    <td><strong>{{ $statistik['cakupan_k1'] ?? 0 }}</strong></td>
                    <td><strong>{{ $statistik['total_ibu_hamil'] ?? 0 }}</strong></td>
                    <td><strong>{{ $statistik['persentase_k1'] ?? 0 }}%</strong></td>
                </tr>
                <tr>
                    <td><strong>Cakupan K4</strong></td>
                    <td><strong>{{ $statistik['cakupan_k4'] ?? 0 }}</strong></td>
                    <td><strong>{{ $statistik['total_ibu_hamil'] ?? 0 }}</strong></td>
                    <td><strong>{{ $statistik['persentase_k4'] ?? 0 }}%</strong></td>
                </tr>
                <tr>
                    <td>Total Tenaga Kesehatan Aktif</td>
                    <td>{{ $statistik['total_tenaga'] ?? 0 }}</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">SKRINING RISIKO KEHAMILAN</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kategori Risiko</th>
                    <th>Jumlah</th>
                    <th>Persentase</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalSkrining =
                        ($statistik['skrining_krr'] ?? 0) +
                        ($statistik['skrining_krt'] ?? 0) +
                        ($statistik['skrining_krst'] ?? 0);
                    $persen_krr =
                        $totalSkrining > 0 ? round((($statistik['skrining_krr'] ?? 0) / $totalSkrining) * 100, 2) : 0;
                    $persen_krt =
                        $totalSkrining > 0 ? round((($statistik['skrining_krt'] ?? 0) / $totalSkrining) * 100, 2) : 0;
                    $persen_krst =
                        $totalSkrining > 0 ? round((($statistik['skrining_krst'] ?? 0) / $totalSkrining) * 100, 2) : 0;
                @endphp
                <tr>
                    <td>Risiko Rendah (KRR)</td>
                    <td>{{ $statistik['skrining_krr'] ?? 0 }}</td>
                    <td>{{ $persen_krr }}%</td>
                    <td><span class="badge success">AMAN</span></td>
                </tr>
                <tr>
                    <td>Risiko Tinggi (KRT)</td>
                    <td>{{ $statistik['skrining_krt'] ?? 0 }}</td>
                    <td>{{ $persen_krt }}%</td>
                    <td><span class="badge warning">PERHATIAN</span></td>
                </tr>
                <tr>
                    <td>Risiko Sangat Tinggi (KRST)</td>
                    <td>{{ $statistik['skrining_krst'] ?? 0 }}</td>
                    <td>{{ $persen_krst }}%</td>
                    <td><span class="badge danger">PRIORITAS</span></td>
                </tr>
                <tr style="background: #F3F4F6; font-weight: bold;">
                    <td>Total Skrining</td>
                    <td>{{ $totalSkrining }}</td>
                    <td>100%</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>{{ $puskesmas->kabupaten }}, {{ now()->format('d F Y') }}</p>
            <p>Kepala Puskesmas</p>
            <div class="signature-line"></div>
            <p><strong>{{ $puskesmas->kepala_puskesmas }}</strong></p>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Informasi Kesehatan Ibu Hamil (SIKASIH)</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
    </div>
</body>

</html>
