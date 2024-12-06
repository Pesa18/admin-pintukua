<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kerja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header-table {
            border: none;
        }
        .header-table td {
            padding: 5px;
        }
        @media print {
            thead {
                display: table-header-group; /* Menampilkan header di setiap halaman */
            }
            tbody {
                page-break-inside: avoid; /* Menghindari pemotongan data di tengah baris */
            }
            table {
                page-break-inside: auto;
            }
        }
        .signature-table {
            width: 100%;
            margin-top: 20px;
        }
        .signature-table td {
            vertical-align: top;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Laporan Kerja</h1>
    <table class="header-table">
        <tr>
            <td><strong>Nama</strong></td>
            <td>: {{ $data_pegawai?->name }}</td>
        </tr>
        <tr>
            <td><strong>NIP</strong></td>
            <td>: {{ $data_pegawai?->nip }}</td>
        </tr>
        <tr>
            <td><strong>Level Jabatan</strong></td>
            <td>: {{ $data_pegawai?->position }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>: {{ $data_pegawai?->status }}</td>
        </tr>
        <tr>
            <td><strong>Golongan Ruang</strong></td>
            <td>: {{ $data_pegawai?->grade }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal dicetak</strong></td>
            <td>: {{ \Carbon\Carbon::now() }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kegiatan</th>
                <th>Pekerjaan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($query as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item?->kegiatan }}</td>
                <td>{{ $item?->deskripsi_pekerjaan}}</td>
                <td>{{ $item?->tanggal }}</td>
            </tr>
            @endforeach
            
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <strong>Mengetahui,</strong><br>
                Kepala {{ $data_kua?->name }}<br><br>
                <br>
                <br>
                <br>
                <strong>{{ $kepala?->name }}</strong><br>
                NIP. {{ $kepala?->nip }}
            </td>
            <td style="text-align: right;">
                Kuningan, {{ \Carbon\Carbon::create($titimangsa)->translatedFormat('d F Y') }}<br>
                Penyusun<br><br>
                <br>
                <br>
                <br>
                <strong>{{ $data_pegawai?->name }}</strong><br>
                NIP. {{ $data_pegawai?->nip }}
            </td>
        </tr>
    </table>
</body>
</html>
