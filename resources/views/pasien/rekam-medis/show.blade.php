@extends('layouts.app')
@section('title', 'Detail Rekam Medis')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span>
<a href="{{ route('pasien.rekam-medis.index') }}">Rekam Medis</a><span>/</span><span>Detail</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Rekam Medis</h1>
        <p class="page-subtitle">{{ $rm->tanggal_periksa->format('d F Y') }}</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <a href="{{ route('pasien.rekam-medis.pdf', $rm->id) }}" target="_blank" class="btn btn-primary"
           style="display:flex;align-items:center;gap:0.5rem;">
            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Unduh PDF
        </a>
        <a href="{{ route('pasien.rekam-medis.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:1.5rem;" class="rm-grid">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <!-- Info kunjungan -->
        <div class="card">
            <div class="card-header">Informasi Kunjungan</div>
            <div class="card-body" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:0.75rem;">
                <div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Tanggal Periksa</div>
                    <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $rm->tanggal_periksa->format('d F Y') }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Dokter</div>
                    <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $rm->dokter->user->name }}</div>
                </div>
                @if($rm->antrian && $rm->antrian->layanan)
                <div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Layanan</div>
                    <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $rm->antrian->layanan->nama }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">No. Antrian</div>
                    <div style="font-weight:600;color:#2563eb;font-family:monospace;margin-top:0.25rem;">{{ $rm->antrian->no_antrian }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Anamnesis -->
        @if($rm->anamnesis)
        <div class="card">
            <div class="card-header">Keluhan yang Disampaikan</div>
            <div class="card-body" style="font-size:0.9375rem;color:#334155;line-height:1.7;">{{ $rm->anamnesis }}</div>
        </div>
        @endif

        <!-- Diagnosa -->
        <div class="card" style="border-left:4px solid #2563eb;">
            <div class="card-header">Diagnosa</div>
            <div class="card-body" style="font-size:0.9375rem;color:#1e293b;font-weight:500;line-height:1.7;">{{ $rm->diagnosa }}</div>
        </div>

        <!-- Tindakan -->
        @if($rm->tindakan)
        <div class="card">
            <div class="card-header">Tindakan yang Dilakukan</div>
            <div class="card-body" style="font-size:0.9375rem;color:#334155;line-height:1.7;">{{ $rm->tindakan }}</div>
        </div>
        @endif

        <!-- Resep Obat -->
        @if($rm->resep && count($rm->resep) > 0)
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;gap:0.75rem;">
                <svg style="width:1rem;height:1rem;color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Resep Obat
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:0.75rem 1rem;text-align:left;font-weight:600;color:#64748b;font-size:0.8125rem;border-bottom:1px solid #e2e8f0;">Nama Obat</th>
                            <th style="padding:0.75rem 1rem;text-align:left;font-weight:600;color:#64748b;font-size:0.8125rem;border-bottom:1px solid #e2e8f0;">Dosis</th>
                            <th style="padding:0.75rem 1rem;text-align:left;font-weight:600;color:#64748b;font-size:0.8125rem;border-bottom:1px solid #e2e8f0;">Aturan Pakai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rm->resep as $r)
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:0.875rem 1rem;font-weight:600;color:#1e293b;">{{ $r['obat'] ?? '-' }}</td>
                            <td style="padding:0.875rem 1rem;color:#475569;">{{ $r['dosis'] ?? '-' }}</td>
                            <td style="padding:0.875rem 1rem;color:#475569;">{{ $r['aturan'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($rm->catatan)
        <div class="card" style="border-left:4px solid #10b981;">
            <div class="card-header">Catatan dari Dokter</div>
            <div class="card-body" style="font-size:0.875rem;color:#334155;line-height:1.7;">{{ $rm->catatan }}</div>
        </div>
        @endif
    </div>

    <!-- Vital Signs -->
    <div>
        <div class="card" style="position:sticky;top:5rem;">
            <div class="card-header">Vital Signs</div>
            <div class="card-body">
                @php $vitals = [
                    ['icon'=>'❤️','label'=>'Tekanan Darah','val'=>$rm->tekanan_darah,'unit'=>'mmHg'],
                    ['icon'=>'⚖️','label'=>'Berat Badan','val'=>$rm->berat_badan,'unit'=>'kg'],
                    ['icon'=>'📏','label'=>'Tinggi Badan','val'=>$rm->tinggi_badan,'unit'=>'cm'],
                    ['icon'=>'🌡️','label'=>'Suhu Tubuh','val'=>$rm->suhu_tubuh,'unit'=>'°C'],
                ]; @endphp
                @foreach($vitals as $v)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 0;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:#64748b;">
                        <span>{{ $v['icon'] }}</span>{{ $v['label'] }}
                    </div>
                    <div style="font-weight:700;color:{{ $v['val'] ? '#1e293b' : '#cbd5e1' }};">
                        {{ $v['val'] ? $v['val'].' '.$v['unit'] : '—' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.rm-grid{grid-template-columns:1fr !important;}}</style>
@endsection
