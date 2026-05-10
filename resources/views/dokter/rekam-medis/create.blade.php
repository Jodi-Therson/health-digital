@extends('layouts.app')
@section('title', 'Buat Rekam Medis')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Buat Rekam Medis</h1></div>
    <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
@if($errors->any())<div class="alert alert-error"><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif

<form method="POST" action="{{ route('dokter.rekam-medis.store') }}" x-data="{loading:false,resep:[{obat:'',dosis:'',aturan:''}]}" @submit="loading=true">
    @csrf
    @if($antrian)
    <input type="hidden" name="antrian_id" value="{{ $antrian->id }}">
    <input type="hidden" name="pasien_id" value="{{ $antrian->pasien->id }}">
    @else
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;" class="form-grid">
        <div class="form-group">
            <label class="form-label">Antrian ID <span style="color:#ef4444;">*</span></label>
            <input type="text" name="antrian_id" value="{{ old('antrian_id') }}" class="form-input" placeholder="ID Antrian" required>
        </div>
        <div class="form-group">
            <label class="form-label">Pasien <span style="color:#ef4444;">*</span></label>
            <select name="pasien_id" class="form-input" required>
                <option value="">-- Pilih Pasien --</option>
                @foreach($pasiens as $p)
                <option value="{{ $p->id }}" {{ old('pasien_id')==$p->id?'selected':'' }}>{{ $p->user->name }} ({{ $p->nik }})</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    @if($antrian)
    <div class="card" style="background:#f0fdf4;border:1px solid #bbf7d0;margin-bottom:1.5rem;">
        <div class="card-body" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;">
            <div><div style="font-size:0.75rem;color:#64748b;font-weight:600;">Pasien</div><div style="font-weight:700;color:#1e293b;">{{ $antrian->pasien->user->name }}</div></div>
            <div><div style="font-size:0.75rem;color:#64748b;font-weight:600;">No. Antrian</div><div style="font-weight:700;color:#2563eb;font-family:monospace;">{{ $antrian->no_antrian }}</div></div>
            <div><div style="font-size:0.75rem;color:#64748b;font-weight:600;">Layanan</div><div style="font-weight:700;">{{ $antrian->layanan->nama }}</div></div>
            <div><div style="font-size:0.75rem;color:#64748b;font-weight:600;">Keluhan</div><div style="font-size:0.875rem;color:#475569;">{{ Str::limit($antrian->keluhan, 60) }}</div></div>
        </div>
    </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="form-grid">
        <div class="card">
            <div class="card-header">Vital Signs</div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tanggal Periksa</label>
                    <input type="date" name="tanggal_periksa" value="{{ old('tanggal_periksa', date('Y-m-d')) }}" class="form-input" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                    <div class="form-group">
                        <label class="form-label">Tekanan Darah</label>
                        <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah') }}" class="form-input" placeholder="120/80">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Suhu (°C)</label>
                        <input type="number" name="suhu_tubuh" value="{{ old('suhu_tubuh') }}" class="form-input" placeholder="36.5" step="0.1" min="30" max="45">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berat (kg)</label>
                        <input type="number" name="berat_badan" value="{{ old('berat_badan') }}" class="form-input" placeholder="65" step="0.1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tinggi (cm)</label>
                        <input type="number" name="tinggi_badan" value="{{ old('tinggi_badan') }}" class="form-input" placeholder="165" step="0.1">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Pemeriksaan</div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Anamnesis (Keluhan Detail)</label>
                    <textarea name="anamnesis" rows="3" class="form-input" placeholder="Keluhan yang disampaikan pasien...">{{ old('anamnesis') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Diagnosa <span style="color:#ef4444;">*</span></label>
                    <textarea name="diagnosa" rows="2" class="form-input {{ $errors->has('diagnosa')?'error':'' }}" placeholder="Diagnosa (ICD-10)..." required>{{ old('diagnosa') }}</textarea>
                    @error('diagnosa')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tindakan yang Dilakukan</label>
                    <textarea name="tindakan" rows="2" class="form-input" placeholder="Tindakan medis yang diberikan...">{{ old('tindakan') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Resep -->
    <div class="card" style="margin-top:1.5rem;">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <span>Resep Obat</span>
            <button type="button" @click="resep.push({obat:'',dosis:'',aturan:''})" class="btn btn-secondary btn-sm">+ Tambah Obat</button>
        </div>
        <div class="card-body">
            <template x-for="(r, i) in resep" :key="i">
                <div style="display:grid;grid-template-columns:2fr 1fr 2fr auto;gap:0.75rem;margin-bottom:0.75rem;align-items:end;">
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Nama Obat</label>
                        <input type="text" :name="'resep_obat['+i+']'" x-model="r.obat" class="form-input" placeholder="Nama obat">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Dosis</label>
                        <input type="text" :name="'resep_dosis['+i+']'" x-model="r.dosis" class="form-input" placeholder="500mg">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Aturan Pakai</label>
                        <input type="text" :name="'resep_aturan['+i+']'" x-model="r.aturan" class="form-input" placeholder="3x sehari sesudah makan">
                    </div>
                    <div>
                        <button type="button" @click="resep.splice(i,1)" class="btn btn-danger btn-sm" x-show="resep.length > 1">×</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Catatan -->
    <div class="card" style="margin-top:1rem;">
        <div class="card-body">
            <div class="form-group" style="margin:0;">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" class="form-input" placeholder="Catatan atau saran untuk pasien...">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1.5rem;">
        <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary" :disabled="loading">
            <span x-show="!loading">Simpan Rekam Medis</span>
            <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><div class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></div>Menyimpan...</span>
        </button>
    </div>
</form>
<style>@media(max-width:768px){.form-grid{grid-template-columns:1fr !important;}}</style>
@endsection
