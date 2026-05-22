@extends('layouts.app')
@section('title', 'Detail Rekam Medis')
@section('sidebar')@include('perawat._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Rekam Medis — {{ $rm->pasien->user->name }}</h1><p class="page-subtitle">{{ $rm->tanggal_periksa->format('d F Y') }}</p></div>
    <a href="{{ route('perawat.rekam-medis.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;" class="rm-grid">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        @if($rm->anamnesis)<div class="card"><div class="card-header">Anamnesis</div><div class="card-body" style="font-size:0.9375rem;color:#334155;line-height:1.7;">{{ $rm->anamnesis }}</div></div>@endif
        <div class="card" style="border-left:4px solid #2563eb;"><div class="card-header">Diagnosa</div><div class="card-body" style="font-size:0.9375rem;font-weight:500;color:#1e293b;">{{ $rm->diagnosa }}</div></div>
        @if($rm->tindakan)<div class="card"><div class="card-header">Tindakan</div><div class="card-body">{{ $rm->tindakan }}</div></div>@endif

        <!-- Tambah catatan perawat -->
        <div class="card" style="border-left:4px solid #10b981;">
            <div class="card-header">Catatan Perawat</div>
            <div class="card-body">
                @if($rm->antrian && $rm->antrian->catatan_perawat)
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:0.625rem;padding:1rem;font-size:0.875rem;color:#334155;margin-bottom:1rem;">{{ $rm->antrian->catatan_perawat }}</div>
                @endif
                <form method="POST" action="{{ route('perawat.rekam-medis.catatan', $rm->id) }}">
                    @csrf @method('PATCH')
                    <div class="form-group" style="margin-bottom:0.75rem;">
                        <textarea name="catatan_perawat" rows="3" class="form-input" placeholder="Tambah/perbarui catatan perawat...">{{ optional($rm->antrian)->catatan_perawat }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">Simpan Catatan</button>
                </form>
            </div>
        </div>
    </div>
    <div>
        <div class="card" style="position:sticky;top:5rem;">
            <div class="card-header">Info Pasien</div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid #f1f5f9;">
                    <img src="{{ $rm->pasien->user->avatar_url }}" style="width:3rem;height:3rem;border-radius:50%;object-fit:cover;">
                    <div><div style="font-weight:700;color:#1e293b;">{{ $rm->pasien->user->name }}</div><div style="font-size:0.8125rem;color:#64748b;">NIK: {{ $rm->pasien->nik }}</div></div>
                </div>
                <div style="font-size:0.875rem;color:#64748b;">Dokter: <strong style="color:#1e293b;">{{ $rm->dokter->user->name }}</strong></div>
                @php $vitals = [['','Tekanan Darah',$rm->tekanan_darah,'mmHg'],['','Suhu',$rm->suhu_tubuh,'°C'],['','Berat',$rm->berat_badan,'kg']]; @endphp
                @foreach($vitals as [$icon,$label,$val,$unit])
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid #f1f5f9;font-size:0.875rem;">
                    <span style="color:#64748b;">{{ $label }}</span>
                    <span style="font-weight:600;">{{ $val ? $val.' '.$unit : '—' }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.rm-grid{grid-template-columns:1fr !important;}}</style>
@endsection
