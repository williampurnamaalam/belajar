<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; color: #000; font-size: 14px; margin: 0; padding: 20px; }
        .doc-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 15px; margin-bottom: 20px; }
        .doc-header h2 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .doc-header p { margin: 5px 0 0; font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 10px; vertical-align: top; }
        th { background-color: #f0f0f0; -webkit-print-color-adjust: exact; }
        
        .nominal { font-weight: bold; text-align: right; white-space: nowrap; }
    </style>
</head>
<body>

    <div class="doc-header">
        <h2>Laporan Aktivitas & Transaksi Karyawan</h2>
        <p>
            Periode: 
            @if($request->bulan && $request->tahun)
                {{ date('F', mktime(0, 0, 0, $request->bulan, 1)) }} {{ $request->tahun }}
            @elseif($request->tahun)
                Tahun {{ $request->tahun }}
            @else
                Semua Data
            @endif
            | Karyawan: {{ $request->karyawan ?: 'Semua' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="25%">Karyawan</th>
                <th>Detail Tugas & Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $row)
            <tr>
                <td align="center">{{ \Carbon\Carbon::parse($row->tanggal_kirim)->format('d/m/Y') }}</td>
                <td style="text-transform: capitalize; font-weight:bold;">{{ $row->user->nama ?? 'Unknown' }}</td>
                <td>
                    <strong>{{ $row->judul_laporan }}</strong>
                    <div style="white-space: pre-line; margin-top: 8px;">{{ $row->deskripsi }}</div>
                    
                    @if(($row->nominal_transaksi ?? 0) > 0)
                        <div style="margin-top: 15px;">
                            <strong>Nominal Transaksi:</strong> <span style="color: #000;">Rp {{ number_format($row->nominal_transaksi, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" align="center">Tidak ada data untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" align="right" style="font-size: 16px;">TOTAL KESELURUHAN:</th>
                <th style="font-size: 16px; text-align: left;">Rp {{ number_format($total_nominal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <script>
        // Otomatis muncul popup print saat tab baru terbuka
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>