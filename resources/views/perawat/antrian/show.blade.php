@extends('layouts.app')
@section('title', 'Detail Antrian')
@section('sidebar')@include('perawat._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Detail Antrian {{ $antrian->no_antrian }}</h1></div>
    <a href="{{ route('perawat.antrian.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="detail-grid">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <div class="card">
            <div class="card-header">Informasi Antrian</div>
            <div class="card-body">
                @php $rows = [
                    ['l'=>'No. Antrian','v'=>$antrian->no_antrian,'mono'=>true],
                    ['l'=>'Pasien','v'=>$antrian->pasien->user->name],
                    ['l'=>'Dokter','v'=>$antrian->dokter->user->name],
                    ['l'=>'Layanan','v'=>$antrian->layanan->nama],
                    ['l'=>'Tanggal','v'=>$antrian->tanggal->format('d F Y')],
                    ['l'=>'Status','v'=>$antrian->status_label,'badge'=>$antrian->status_badge_color],
                ]; @endphp
                @foreach($rows as $r)
                <div style="display:flex;gap:1rem;padding:0.625rem 0;border-bottom:1px solid #f1f5f9;">
                    <div style="min-width:110px;font-size:0.8125rem;color:#64748b;font-weight:500;">{{ $r['l'] }}</div>
                    <div>
                        @if(isset($r['badge']))<span class="badge badge-{{ $r['badge'] }}">{{ $r['v'] }}</span>
                        @elseif(isset($r['mono']))<span style="font-family:monospace;font-weight:700;color:#2563eb;">{{ $r['v'] }}</span>
                        @else<span style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $r['v'] }}</span>@endif
                    </div>
                </div>
                @endforeach
                <div style="display:flex;gap:1rem;padding:0.625rem 0;">
                    <div style="min-width:110px;font-size:0.8125rem;color:#64748b;font-weight:500;">Keluhan</div>
                    <div style="font-size:0.875rem;color:#334155;">{{ $antrian->keluhan }}</div>
                </div>
            </div>
        </div>

        @if($antrian->rekamMedis)
        <div class="card" style="border-left:3px solid #2563eb;">
            <div class="card-header">Rekam Medis</div>
            <div class="card-body">
                <div style="font-size:0.8125rem;color:#64748b;">Diagnosa:</div>
                <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $antrian->rekamMedis->diagnosa }}</div>
                <a href="{{ route('perawat.rekam-medis.show', $antrian->rekamMedis->id) }}" class="btn btn-secondary btn-sm" style="margin-top:0.75rem;">Lihat Detail</a>
            </div>
        </div>
        @endif
    </div>

    <!-- Catatan perawat -->
    <div class="card">
        <div class="card-header">Catatan Perawat</div>
        <div class="card-body">
            @if($antrian->catatan_perawat)
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:0.625rem;padding:1rem;font-size:0.875rem;color:#334155;line-height:1.6;margin-bottom:1rem;">{{ $antrian->catatan_perawat }}</div>
            @endif
            <form method="POST" action="{{ route('perawat.rekam-medis.catatan', $antrian->rekamMedis->id ?? 0) }}" x-data="{loading:false}" @submit="loading=true">
                @csrf @method('PATCH')
                <div class="form-group">
                    <label class="form-label">{{ $antrian->catatan_perawat ? 'Perbarui' : 'Tambah' }} Catatan</label>
                    <textarea name="catatan_perawat" rows="4" class="form-input" placeholder="Catatan perawat...">{{ $antrian->catatan_perawat }}</textarea>
                </div>
                @if($antrian->rekamMedis)
                <button type="submit" class="btn btn-primary" :disabled="loading">Simpan Catatan</button>
                @else
                <div class="alert alert-warning">Rekam medis belum dibuat oleh dokter.</div>
                @endif
            </form>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.detail-grid{grid-template-columns:1fr !important;}}</style>
@endsection
