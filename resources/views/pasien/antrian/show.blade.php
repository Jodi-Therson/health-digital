@extends('layouts.app')
@section('title', 'Detail Antrian')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span>
<a href="{{ route('pasien.antrian.index') }}">Antrian</a><span>/</span>
<span>{{ $antrian->no_antrian }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Antrian</h1>
        <p class="page-subtitle">{{ $antrian->no_antrian }}</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <a href="{{ route('pasien.antrian.index') }}" class="btn btn-secondary">← Kembali</a>
        @if($antrian->status === 'menunggu')
        <form method="POST" action="{{ route('pasien.antrian.update', $antrian->id) }}" x-ref="batalForm">
            @csrf @method('PUT')
            <button type="button" name="action" value="batal"
                @click="triggerConfirm(
                    'Batalkan Antrian',
                    'Anda yakin ingin membatalkan antrian {{ $antrian->no_antrian }}? Tindakan ini tidak dapat dibatalkan.',
                    () => { $refs.batalForm.submit() },
                    'danger'
                )"
                class="btn" style="background:#fee2e2;color:#ef4444;border:1px solid #fca5a5;">Batalkan Antrian</button>
        </form>
        @endif
    </div>
</div>

<!-- Nomor antrian hero -->
<div style="background:linear-gradient(135deg,{{ $antrian->status==='dipanggil' ? '#2563eb,#1d4ed8' : ($antrian->status==='selesai' ? '#10b981,#059669' : ($antrian->status==='batal' ? '#ef4444,#dc2626' : '#f59e0b,#d97706') ) }}); border-radius:1rem; padding:2rem; margin-bottom:1.5rem; text-align:center; color:white;">
    <div style="font-size:0.875rem; opacity:0.8; margin-bottom:0.5rem;">Nomor Antrian</div>
    <div style="font-size:3rem; font-weight:800; font-family:monospace; letter-spacing:0.1em;">{{ $antrian->no_antrian }}</div>
    <div style="margin-top:0.75rem;">
        <span class="badge" style="background:rgba(255,255,255,0.25); color:white; font-size:0.875rem; padding:0.5rem 1.5rem;">
            {{ $antrian->status_label }}
        </span>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;" class="detail-grid">
    <!-- Info antrian -->
    <div class="card">
        <div class="card-header">Informasi Kunjungan</div>
        <div class="card-body">
            @php $rows = [
                ['label'=>'Layanan', 'val'=>$antrian->layanan->nama],
                ['label'=>'Dokter', 'val'=>$antrian->dokter->user->name],
                ['label'=>'Spesialisasi', 'val'=>$antrian->dokter->spesialisasi],
                ['label'=>'Tanggal', 'val'=>$antrian->tanggal->format('d F Y')],
                ['label'=>'Didaftarkan', 'val'=>$antrian->created_at->format('d M Y, H:i')],
            ]; @endphp
            @foreach($rows as $r)
            <div style="display:flex; gap:1rem; padding:0.625rem 0; border-bottom:1px solid #f1f5f9;">
                <div style="min-width:120px; font-size:0.8125rem; color:#64748b; font-weight:500;">{{ $r['label'] }}</div>
                <div style="font-size:0.875rem; color:#1e293b; font-weight:600;">{{ $r['val'] }}</div>
            </div>
            @endforeach
            <div style="display:flex; gap:1rem; padding:0.625rem 0;">
                <div style="min-width:120px; font-size:0.8125rem; color:#64748b; font-weight:500;">Keluhan</div>
                <div style="font-size:0.875rem; color:#1e293b;">{{ $antrian->keluhan }}</div>
            </div>
        </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:1rem;">
        <!-- Rekam medis -->
        @if($antrian->rekamMedis)
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <span>Rekam Medis</span>
                <a href="{{ route('pasien.rekam-medis.show', $antrian->rekamMedis->id) }}" class="btn btn-secondary btn-sm">Lihat Detail</a>
            </div>
            <div class="card-body">
                <div style="font-size:0.875rem; color:#64748b; margin-bottom:0.5rem;">Diagnosa:</div>
                <div style="font-size:0.9375rem; font-weight:600; color:#1e293b;">{{ $antrian->rekamMedis->diagnosa }}</div>
            </div>
        </div>
        @endif

        <!-- Pembayaran -->
        @if($antrian->pembayaran)
        <div class="card">
            <div class="card-header">Tagihan</div>
            <div class="card-body">
                <div style="font-size:1.5rem; font-weight:800; color:#2563eb;">{{ $antrian->pembayaran->jumlah_format }}</div>
                <div style="font-size:0.875rem; color:#64748b; margin:0.5rem 0;">{{ $antrian->pembayaran->kode_invoice }}</div>
                <span class="badge badge-{{ $antrian->pembayaran->status_badge_color }}">{{ $antrian->pembayaran->status_label }}</span>
                @if($antrian->pembayaran->status === 'menunggu')
                <div style="margin-top:1rem;">
                    <a href="{{ route('pasien.pembayaran.show', $antrian->pembayaran->id) }}" class="btn btn-primary btn-sm">Bayar Sekarang</a>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Catatan perawat -->
        @if($antrian->catatan_perawat)
        <div class="card" style="border-left:3px solid #10b981;">
            <div class="card-header">Catatan Perawat</div>
            <div class="card-body" style="font-size:0.875rem; color:#334155;">{{ $antrian->catatan_perawat }}</div>
        </div>
        @endif
    </div>
</div>
<style>@media(max-width:768px){.detail-grid{grid-template-columns:1fr !important;}}</style>
@endsection
