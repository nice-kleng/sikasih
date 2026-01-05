<!DOCTYPE html>
<html>

<head>
    <title>Kartu ANC - {{ $ibuHamil->nama_lengkap }}</title>
    <style>
        /* Your PDF styling here */
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>KARTU PEMERIKSAAN ANTENATAL</h2>
        <h3>{{ $puskesmas->nama_puskesmas }}</h3>
    </div>

    <div class="info">
        <table>
            <tr>
                <td>No. RM</td>
                <td>: {{ $ibuHamil->no_rm }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>: {{ $ibuHamil->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>HPHT</td>
                <td>: {{ $ibuHamil->hpht?->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>HPL</td>
                <td>: {{ $ibuHamil->hpl?->format('d M Y') }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
