@extends('layouts.app')
@section('title', 'Detail Rekam Medis')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Rekam Medis — {{ $rm->pasien->user->name }}</h1><p class="page-subtitle">{{ $rm->tanggal_periksa->format('d F Y') }}</p></div>
    <div style="display:flex;gap:0.75rem;">
        <a href="{{ route('dokter.rekam-medis.edit', $rm->id) }}" class="btn btn-secondary">Edit</a>
        <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>
</div>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;" class="rm-grid">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        @if($rm->anamnesis)<div class="card"><div class="card-header">Anamnesis</div><div class="card-body" style="font-size:0.9375rem;color:#334155;line-height:1.7;">{{ $rm->anamnesis }}</div></div>@endif
        <div class="card" style="border-left:4px solid #2563eb;"><div class="card-header">Diagnosa</div><div class="card-body" style="font-size:0.9375rem;font-weight:500;color:#1e293b;line-height:1.7;">{{ $rm->diagnosa }}</div></div>
        @if($rm->tindakan)<div class="card"><div class="card-header">Tindakan</div><div class="card-body" style="font-size:0.9375rem;color:#334155;line-height:1.7;">{{ $rm->tindakan }}</div></div>@endif
        @if($rm->resep && count($rm->resep) > 0)
        <div class="card">
            <div class="card-header">Resep Obat</div>
            <div class="table-container" style="border:none;">
                <table class="data-table"><thead><tr><th>Obat</th><th>Dosis</th><th>Aturan</th></tr></thead>
                <tbody>@foreach($rm->resep as $r)<tr><td style="font-weight:600;">{{ $r['obat'] }}</td><td>{{ $r['dosis'] }}</td><td>{{ $r['aturan'] }}</td></tr>@endforeach</tbody></table>
            </div>
        </div>
        @endif
        @if($rm->catatan)<div class="card" style="border-left:4px solid #10b981;"><div class="card-header">Catatan</div><div class="card-body" style="font-size:0.875rem;color:#334155;">{{ $rm->catatan }}</div></div>@endif
    </div>
    <div>
        <div class="card" style="position:sticky;top:5rem;">
            <div class="card-header">Info Pasien & Vital Signs</div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid #f1f5f9;">
                    <img src="{{ $rm->pasien->user->avatar_url }}" style="width:3rem;height:3rem;border-radius:50%;object-fit:cover;">
                    <div><div style="font-weight:700;color:#1e293b;">{{ $rm->pasien->user->name }}</div><div style="font-size:0.8125rem;color:#64748b;">NIK: {{ $rm->pasien->nik }}</div></div>
                </div>
                @php $vitals = [['❤️','Tekanan Darah',$rm->tekanan_darah,'mmHg'],['⚖️','Berat Badan',$rm->berat_badan,'kg'],['📏','Tinggi Badan',$rm->tinggi_badan,'cm'],['🌡️','Suhu Tubuh',$rm->suhu_tubuh,'°C']]; @endphp
                @foreach($vitals as [$icon,$label,$val,$unit])
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.625rem 0;border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:0.875rem;color:#64748b;">{{ $icon }} {{ $label }}</div>
                    <div style="font-weight:700;color:#1e293b;">{{ $val ? $val.' '.$unit : '—' }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.rm-grid{grid-template-columns:1fr !important;}}</style>
@endsection
