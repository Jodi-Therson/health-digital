<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan HealthDigital</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h1 { color: #1e3a8a; margin: 0 0 5px 0; font-size: 24px; }
        .header p { margin: 0; color: #64748b; font-size: 14px; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 16px; font-weight: bold; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; }
        .text-right { text-align: right; }
        .summary-box { background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .summary-item { display: inline-block; width: 30%; font-size: 14px; }
        .summary-label { color: #64748b; font-size: 12px; }
        .summary-value { font-weight: bold; color: #15803d; font-size: 18px; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HealthDigital Platform</h1>
        <p>Laporan Operasional & Keuangan</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">Total Antrian</div>
            <div class="summary-value">{{ $totalAntrian }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Antrian Selesai</div>
            <div class="summary-value">{{ $antrianSelesai }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Pendapatan</div>
            <div class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Rincian Antrian Selesai ({{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</div>
        <table>
            <thead>
                <tr>
                    <th>No. Antrian</th>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Layanan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($antrians as $a)
                <tr>
                    <td>{{ $a->no_antrian }}</td>
                    <td>{{ $a->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $a->pasien->user->name }}</td>
                    <td>{{ $a->dokter->user->name }}</td>
                    <td>{{ $a->layanan->nama }}</td>
                    <td>{{ ucfirst($a->status) }}</td>
                </tr>
                @endforeach
                @if($antrians->isEmpty())
                <tr><td colspan="6" style="text-align:center;">Tidak ada data antrian pada periode ini.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Pendapatan per Layanan</div>
        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-right">Jumlah Antrian (Selesai)</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @php $totalSemua = 0; @endphp
                @foreach($pendapatanLayanan as $pl)
                @php $totalSemua += $pl->total; @endphp
                <tr>
                    <td>Poli {{ $pl->nama }}</td>
                    <td class="text-right">{{ $pl->jumlah_antrian }}</td>
                    <td class="text-right">Rp {{ number_format($pl->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">Total Keseluruhan</th>
                    <th class="text-right">Rp {{ number_format($totalSemua, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} | HealthDigital Platform v1.0</p>
    </div>
</body>
</html>
