<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Rekam Medis - {{ $rm->pasien->user->name }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; background: white; }
    .header { background: #1e3a8a; color: white; padding: 20px 30px; margin-bottom: 0; }
    .header h1 { font-size: 20px; font-weight: 700; letter-spacing: -0.5px; }
    .header p { font-size: 11px; opacity: 0.8; margin-top: 4px; }
    .sub-header { background: #dbeafe; padding: 12px 30px; border-bottom: 2px solid #2563eb; margin-bottom: 20px; }
    .sub-header table { width: 100%; }
    .sub-header td { font-size: 11px; color: #1e40af; padding: 2px 0; }
    .sub-header .label { font-weight: 600; width: 130px; }
    .content { padding: 0 30px 30px; }
    .section { margin-bottom: 18px; }
    .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #2563eb; border-bottom: 1px solid #bfdbfe; padding-bottom: 5px; margin-bottom: 10px; }
    .section-content { font-size: 12px; color: #334155; line-height: 1.6; }
    .diagnosa-box { background: #eff6ff; border-left: 4px solid #2563eb; padding: 10px 14px; border-radius: 4px; font-weight: 600; }
    .vital-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
    .vital-table td { padding: 6px 8px; border: 1px solid #e2e8f0; font-size: 11px; }
    .vital-table tr:nth-child(even) td { background: #f8fafc; }
    .vital-table .label { font-weight: 600; color: #64748b; width: 40%; }
    .resep-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
    .resep-table th { background: #1e3a8a; color: white; padding: 7px 10px; text-align: left; font-size: 11px; }
    .resep-table td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
    .resep-table tr:nth-child(even) td { background: #f8fafc; }
    .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 12px; display: flex; justify-content: space-between; }
    .footer .disclaimer { font-size: 10px; color: #94a3b8; }
    .two-col { display: flex; gap: 20px; }
    .two-col .col { flex: 1; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; background: #dbeafe; color: #1e40af; }
</style>
</head>
<body>

<div class="header">
    <h1>HealthDigital — Rekam Medis</h1>
    <p>Platform Layanan Kesehatan Digital Terpadu</p>
</div>

<div class="sub-header">
    <table>
        <tr>
            <td class="label">Nama Pasien</td>
            <td>: {{ $rm->pasien->user->name }}</td>
            <td class="label">Tanggal Periksa</td>
            <td>: {{ $rm->tanggal_periksa->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td>: {{ $rm->pasien->nik ?? '-' }}</td>
            <td class="label">Dokter</td>
            <td>: {{ $rm->dokter->user->name }}</td>
        </tr>
        @if($rm->pasien->tanggal_lahir)
        <tr>
            <td class="label">Usia</td>
            <td>: {{ $rm->pasien->umur }} tahun ({{ $rm->pasien->tanggal_lahir->format('d M Y') }})</td>
            @if($rm->antrian && $rm->antrian->layanan)
            <td class="label">Layanan</td>
            <td>: {{ $rm->antrian->layanan->nama }}</td>
            @endif
        </tr>
        @endif
        @if($rm->antrian)
        <tr>
            <td class="label">No. Antrian</td>
            <td>: {{ $rm->antrian->no_antrian }}</td>
            <td></td><td></td>
        </tr>
        @endif
    </table>
</div>

<div class="content">

    {{-- Vital Signs --}}
    <div class="section">
        <div class="section-title">Vital Signs</div>
        <table class="vital-table">
            <tr><td class="label">Tekanan Darah</td><td>{{ $rm->tekanan_darah ? $rm->tekanan_darah . ' mmHg' : '—' }}</td><td class="label">Berat Badan</td><td>{{ $rm->berat_badan ? $rm->berat_badan . ' kg' : '—' }}</td></tr>
            <tr><td class="label">Suhu Tubuh</td><td>{{ $rm->suhu_tubuh ? $rm->suhu_tubuh . ' °C' : '—' }}</td><td class="label">Tinggi Badan</td><td>{{ $rm->tinggi_badan ? $rm->tinggi_badan . ' cm' : '—' }}</td></tr>
        </table>
    </div>

    {{-- Anamnesis --}}
    @if($rm->anamnesis)
    <div class="section">
        <div class="section-title">Keluhan yang Disampaikan</div>
        <div class="section-content">{{ $rm->anamnesis }}</div>
    </div>
    @endif

    {{-- Diagnosa --}}
    <div class="section">
        <div class="section-title">Diagnosa</div>
        <div class="diagnosa-box">{{ $rm->diagnosa }}</div>
    </div>

    {{-- Tindakan --}}
    @if($rm->tindakan)
    <div class="section">
        <div class="section-title">Tindakan yang Dilakukan</div>
        <div class="section-content">{{ $rm->tindakan }}</div>
    </div>
    @endif

    {{-- Resep --}}
    @if($rm->resep && count($rm->resep) > 0)
    <div class="section">
        <div class="section-title">Resep Obat</div>
        <table class="resep-table">
            <thead>
                <tr><th>Nama Obat</th><th>Dosis</th><th>Aturan Pakai</th></tr>
            </thead>
            <tbody>
                @foreach($rm->resep as $r)
                <tr><td><strong>{{ $r['obat'] ?? '-' }}</strong></td><td>{{ $r['dosis'] ?? '-' }}</td><td>{{ $r['aturan'] ?? '-' }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Catatan --}}
    @if($rm->catatan)
    <div class="section">
        <div class="section-title">Catatan dari Dokter</div>
        <div class="section-content">{{ $rm->catatan }}</div>
    </div>
    @endif

    {{-- Footer --}}
    <div style="margin-top:30px;border-top:1px solid #e2e8f0;padding-top:12px;">
        <table style="width:100%;">
            <tr>
                <td style="font-size:10px;color:#94a3b8;">
                    Dokumen ini dicetak dari sistem HealthDigital pada {{ now()->format('d F Y, H:i') }} WIB.<br>
                    Dokumen ini hanya berlaku sebagai rekaman medis dan bukan surat keterangan resmi.
                </td>
                <td style="text-align:right;font-size:10px;color:#64748b;">
                    Diperiksa oleh:<br>
                    <strong style="font-size:12px;color:#1e293b;">{{ $rm->dokter->user->name }}</strong><br>
                    {{ $rm->dokter->spesialisasi ?? 'Dokter Umum' }}
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>
