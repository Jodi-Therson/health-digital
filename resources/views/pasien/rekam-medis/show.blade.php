@extends('layouts.app')
@section('title', 'Detail Rekam Medis')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span>
<a href="{{ route('pasien.rekam-medis.index') }}">Rekam Medis</a><span>/</span><span>Detail</span>
@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Detail Rekam Medis</h1><p class="page-subtitle">{{ $rm->tanggal_periksa->format('d F Y') }}</p></div>
    <a href="{{ route('pasien.rekam-medis.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:1.5rem;" class="rm-grid">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <!-- Info kunjungan -->
        <div class="card">
            <div class="card-header">Informasi Kunjungan</div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                <div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Tanggal Periksa</div>
                    <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $rm->tanggal_periksa->format('d F Y') }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Dokter</div>
                    <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $rm->dokter->user->name }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Layanan</div>
                    <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ optional($rm->antrian->layanan)->nama }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">No. Antrian</div>
                    <div style="font-weight:600;color:#2563eb;font-family:monospace;margin-top:0.25rem;">{{ $rm->antrian->no_antrian }}</div>
                </div>
            </div>
        </div>

        <!-- Anamnesis -->
        @if($rm->anamnesis)
        <div class="card">
            <div class="card-header">Anamnesis (Keluhan)</div>
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

        <!-- Resep -->
        @if($rm->resep && count($rm->resep) > 0)
        <div class="card">
            <div class="card-header">Resep Obat</div>
            <div class="table-container" style="border:none;">
                <table class="data-table">
                    <thead><tr><th>Nama Obat</th><th>Dosis</th><th>Aturan Pakai</th></tr></thead>
                    <tbody>
                        @foreach($rm->resep as $r)
                        <tr>
                            <td style="font-weight:600;">{{ $r['obat'] ?? '-' }}</td>
                            <td>{{ $r['dosis'] ?? '-' }}</td>
                            <td>{{ $r['aturan'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($rm->catatan)
        <div class="card" style="border-left:4px solid #10b981;">
            <div class="card-header">Catatan Tambahan</div>
            <div class="card-body" style="font-size:0.875rem;color:#334155;">{{ $rm->catatan }}</div>
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
                    <div style="font-weight:700;color:#1e293b;">
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
