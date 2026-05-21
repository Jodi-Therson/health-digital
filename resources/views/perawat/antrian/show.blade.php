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

    <!-- Vital Signs & Catatan Perawat -->
    <div class="card">
        <div class="card-header">Vital Signs & Catatan</div>
        <div class="card-body">
            @if($antrian->status === 'menunggu')
            <div class="alert alert-warning" style="background:#fffbeb;color:#b45309;padding:1rem;border-radius:0.5rem;">Panggil pasien terlebih dahulu untuk mengisi vital signs.</div>
            @else
            <form method="POST" action="{{ route('perawat.antrian.vital-signs', $antrian->id) }}" x-data="{loading:false}" @submit="loading=true">
                @csrf @method('PATCH')
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:1rem;margin-bottom:1rem;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Tekanan Darah (mmHg)</label>
                        <input type="text" name="tekanan_darah" class="form-input" value="{{ old('tekanan_darah', $antrian->tekanan_darah) }}" placeholder="120/80">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Suhu Tubuh (°C)</label>
                        <input type="number" step="0.1" name="suhu_tubuh" class="form-input" value="{{ old('suhu_tubuh', $antrian->suhu_tubuh) }}" placeholder="36.5">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="berat_badan" class="form-input" value="{{ old('berat_badan', $antrian->berat_badan) }}" placeholder="60.5">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="tinggi_badan" class="form-input" value="{{ old('tinggi_badan', $antrian->tinggi_badan) }}" placeholder="170">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ $antrian->catatan_perawat ? 'Perbarui' : 'Tambah' }} Catatan</label>
                    <textarea name="catatan_perawat" rows="3" class="form-input" placeholder="Catatan perawat...">{{ old('catatan_perawat', $antrian->catatan_perawat) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" :disabled="loading">Simpan Vital Signs</button>
            </form>
            @endif
        </div>
    </div>
</div>
<style>@media(max-width:768px){.detail-grid{grid-template-columns:1fr !important;}}</style>
@endsection
